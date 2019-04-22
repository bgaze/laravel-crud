<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Bgaze\Crud\Core\Command;
use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;
use Bgaze\Crud\Core\FieldsTemplatesTrait;
use Bgaze\Crud\Definitions;

/**
 * The Migration class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class MigrationClass extends Builder {

    use FieldsTemplatesTrait;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * As it contains a timestamp, we store the file name when generated.
     * 
     * @var string 
     */
    protected $file;

    /**
     * The class constructor
     * 
     * @param \Illuminate\Filesystem\Filesystem $files     The filesystem instance
     * @param \Bgaze\Crud\Core\Command $command            The command instance
     */
    public function __construct(Filesystem $files, Command $command) {
        parent::__construct($files, $command);
        $this->composer = resolve('Illuminate\Support\Composer');
    }

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        if (empty($this->file)) {
            $file = Str::snake($this->crud->getMigrationClass());
            $prefix = date('Y_m_d_His');
            $this->file = database_path("migrations/{$prefix}_{$file}.php");
        }

        return $this->file;
    }

    /**
     * Check that the file to generate doesn't exists.
     * 
     * @return false|string The error message if file exists, false otherwise
     */
    public function fileExists() {
        $file = Str::snake($this->crud->getMigrationClass());
        $files = $this->files->glob(database_path("migrations/*_{$file}.php"));

        if (count($files) === 1) {
            return $this->relativePath($files[0]);
        }

        if (count($files) > 1) {
            return "migrations/*_{$file}.php (" . count($files) . ")";
        }

        return false;
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        // Write migration file.
        $stub = $this->stub('migration');
        $this->replace($stub, '#CONTENT', $this->content());
        $this->generatePhpFile($this->file(), $stub);

        // Update autoload.
        $this->composer->dumpAutoloads();
    }

    /**
     * Build the migration content.
     * 
     * @return string
     */
    protected function content() {
        return $this->crud->content()->map(function(Field $field) {
                    $tmp = $this->fieldTemplate($field);

                    foreach ($field->input()->getArguments() as $k => $v) {
                        $tmp = str_replace("%$k", $this->compileValueForPhp($v), $tmp);
                    }

                    foreach ($field->input()->getOptions() as $k => $v) {
                        if ($v !== null && $v !== false && isset(Definitions::COLUMNS_MODIFIERS[$k])) {
                            $tmp .= str_replace('%value', $this->compileValueForPhp($v), Definitions::COLUMNS_MODIFIERS[$k]);
                        }
                    }

                    return $tmp . ';';
                })->implode("\n");
    }

    /**
     * Get the default template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function defaultTemplate(Field $field) {
        $arguments = array_keys($field->definition()->getArguments());

        if (!empty($arguments)) {
            return '$table->' . $field->command() . '(%' . implode(', %', $arguments) . ')';
        }

        return '$table->' . $field->command() . '()';
    }

}
