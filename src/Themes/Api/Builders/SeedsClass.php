<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Illuminate\Filesystem\Filesystem;
use Bgaze\Crud\Core\Command;
use Bgaze\Crud\Core\Builder;

/**
 * The Seeds class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class SeedsClass extends Builder {

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * The class constructor.
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
        return database_path('seeds/' . $this->crud->getPluralFullStudly() . 'TableSeeder.php');
    }

    /**
     * Build the file.
     */
    public function build() {
        // Write migration file.
        $this->generatePhpFile($this->file(), $this->stub('seeds'));

        // Update autoload.
        $this->composer->dumpAutoloads();
    }

}
