<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Bgaze\Crud\Core\Command;
use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;
use Bgaze\Crud\Core\FieldsTemplatesTrait;

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
        $content = $this->crud->content()->map(function(Field $field) {
            return $this->migrationGroup($field);
        });

        if ($this->crud->softDeletes()) {
            $content->prepend(config('crud-definitions.softDeletes.' . $this->crud->softDeletes()));
        }

        if ($this->crud->timestamps()) {
            $content->prepend(config('crud-definitions.timestamps.' . $this->crud->timestamps()));
        }

        return $content->implode("\n");
    }

    /**
     * Compile content to migration class body line.
     * 
     * @return string
     */
    protected function migrationGroup(Field $field) {
        $tmp = $this->fieldTemplate($field);

        foreach ($field->input()->getArguments() as $k => $v) {
            $tmp = str_replace("%$k", $this->compileValueForPhp($v), $tmp);
        }

        foreach ($field->input()->getOptions() as $k => $v) {
            if ($v) {
                $tmp .= str_replace('%value', $this->compileValueForPhp($v), config("crud-definitions.modifiers.{$k}"));
            }
        }

        return $tmp . ';';
    }

    /**
     * Get the default template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function defaultTemplate(Field $field) {
        return '$table->' . $field->command() . '(%column)';
    }

    /**
     * Get the template for a char field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function charTemplate(Field $field) {
        return '$table->char(%column, %length)';
    }

    /**
     * Get the template for a decimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function decimalTemplate(Field $field) {
        return '$table->decimal(%column, %total, %places)';
    }

    /**
     * Get the template for a double field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function doubleTemplate(Field $field) {
        return '$table->double(%column, %total, %places)';
    }

    /**
     * Get the template for a enum field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function enumTemplate(Field $field) {
        return '$table->enum(%column, %allowed)';
    }

    /**
     * Get the template for a float field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function floatTemplate(Field $field) {
        return '$table->float(%column, %total, %places)';
    }

    /**
     * Get the template for a string field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function stringTemplate(Field $field) {
        return '$table->string(%column, %length)';
    }

    /**
     * Get the template for a unsignedDecimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedDecimalTemplate(Field $field) {
        return '$table->unsignedDecimal(%column, %total, %places)';
    }

    /**
     * Get the template for a index field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function indexTemplate(Field $field) {
        return '$table->index(%columns)';
    }

    /**
     * Get the template for a primaryIndex field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function primaryIndexTemplate(Field $field) {
        return '$table->primary(%columns)';
    }

    /**
     * Get the template for a uniqueIndex field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function uniqueIndexTemplate(Field $field) {
        return '$table->unique(%columns)';
    }

    /**
     * Get the template for a spatialIndex field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function spatialIndexTemplate(Field $field) {
        return '$table->spatialIndex(%columns)';
    }

}
