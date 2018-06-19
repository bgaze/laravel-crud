<?php

namespace Bgaze\Crud\Theme\Builders;

use Illuminate\Filesystem\Filesystem;
use Bgaze\Crud\Core\Crud;
use Bgaze\Crud\Core\Builder;

/**
 * The Seeds class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Seeds extends Builder {

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * The class constructor.
     * 
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Bgaze\Crud\Core\Crud $crud
     */
    public function __construct(Filesystem $files, Crud $crud) {
        parent::__construct($files, $crud);

        $this->composer = resolve('Illuminate\Support\Composer');
    }

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return database_path('seeds/' . $this->crud->getPluralFullStudly() . 'TableSeeder.php');
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        // Write migration file.
        $path = $this->generatePhpFile($this->file(), $this->stub('seeds'));

        // Update autoload.
        $this->composer->dumpAutoloads();

        // Return relative path.
        return $path;
    }

}
