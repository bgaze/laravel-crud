<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Bgaze\Crud\Core\Command;
use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Entry;
use Bgaze\Crud\Core\EntriesTemplatesTrait;
use Bgaze\Crud\Definitions;

/**
 * The Migration class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class MigrationClass extends Builder {

    use EntriesTemplatesTrait;

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
        return $this->crud->content()->map(function(Entry $entry) {
                            return $this->entryTemplate($entry);
                        })
                        ->filter()
                        ->implode("\n");
    }

    /**
     * Add modifiers to template based on entry options and user input.
     * 
     * @param Bgaze\Crud\Core\Entry $entry
     * @param string $template
     */
    protected function addModifiers(Entry $entry, &$template) {
        foreach ($entry->options() as $k => $v) {
            if ($v !== null && $v !== false && isset(Definitions::COLUMNS_MODIFIERS[$k])) {
                $template .= str_replace('%value', $this->compileValueForPhp($v), Definitions::COLUMNS_MODIFIERS[$k]);
            }
        }
    }

    /**
     * Get the default template for a entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function defaultTemplate(Entry $entry) {
        $arguments = $entry->definition()->getArguments();

        if (!empty($arguments)) {
            $template = '$table->' . $entry->command() . '(%' . implode(', %', array_keys($arguments)) . ')';
        } else {
            $template = '$table->' . $entry->command() . '()';
        }

        foreach ($entry->arguments() as $k => $v) {
            $template = str_replace("%$k", $this->compileValueForPhp($v), $template);
        }

        $this->addModifiers($entry, $template);

        return $template . ';';
    }

    /**
     * Get the template for a hasOne entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function hasOneTemplate(Entry $entry) {
        return null;
    }

    /**
     * Get the template for a hasMany entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function hasManyTemplate(Entry $entry) {
        return null;
    }

    /**
     * Get the template for a belongsTo entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function belongsToTemplate(Entry $entry) {
        $column = $entry->option('foreignKey', $entry->related()->getModelCamel() . '_id');
        $template = '$table->unsignedInteger(' . $this->compileValueForPhp($column) . ')';
        $this->addModifiers($entry, $template);
        return $template . ';';
    }

}
