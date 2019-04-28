<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Illuminate\Support\Str;
use Bgaze\Crud\Core\Builder;

/**
 * The Migration class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class MigrationClass extends Builder {

    /**
     * As it contains a timestamp, we store the file name when generated.
     * 
     * @var string 
     */
    protected $file;

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
        // Generate migration content.
        $content = $this->compileAll('migration-content', '// TODO');

        // Write migration file.
        $stub = $this->stub('migration');
        $this->replace($stub, '#CONTENT', $content);
        $this->generatePhpFile($this->file(), $stub);

        // Update autoload.
        resolve('Illuminate\Support\Composer')->dumpAutoloads();
    }

}
