<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * Description of Migration
 *
 * @author bgaze
 */
class Migration extends Builder {

    /**
     * As it is timestamped, we store the file name when generated.
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
            $file = Str::snake($this->getMigrationClass());
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
        $file = Str::snake($this->getMigrationClass());

        if (count($this->crud->files->glob(database_path("migrations/*_{$file}.php")))) {
            return "A '*_{$file}.php' migration file already exists.";
        }
        
        return false;
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        ;
    }

}
