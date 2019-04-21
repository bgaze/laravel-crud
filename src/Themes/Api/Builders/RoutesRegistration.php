<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * Description of Routes
 *
 * @author bgaze
 */
class RoutesRegistration extends Builder {

    /**
     * The routes file that the builder updates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return base_path('routes/api.php');
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $this->files->append($this->file(), $this->stub('routes'));
    }

    /**
     * Check if nothing prevent the builder execution.
     * 
     * By default this function checks that the builder target doesn't already exists
     * 
     * @return false|string     An error message, false otherwise
     */
    public function cannotBuild() {
        if (!$this->files->exists($this->file())) {
            return 'Cannot find routes file ' . $this->relativePath($this->file());
        }

        return false;
    }

    /**
     * Print builder's action summary into console.
     */
    public function summarize() {
        $this->command->dl(' ' . static::name() . ' into', $this->relativePath($this->file()));
    }

    /**
     * Print builder's effect summary into console.
     */
    public function done() {
        $this->command->dl(' Registred routes into', $this->relativePath($this->file()));
    }

}
