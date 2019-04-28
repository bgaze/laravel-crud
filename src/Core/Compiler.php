<?php

namespace Bgaze\Crud\Core;

use Illuminate\Filesystem\Filesystem;
use Bgaze\Crud\Core\CompilerTrait;
use Bgaze\Crud\Core\EntryCompilerTrait;
use Bgaze\Crud\Core\Crud;

/**
 * Description of Compiler
 *
 * @author bgaze
 */
abstract class Compiler {

    use CompilerTrait;
    use EntryCompilerTrait;

    /**
     * The class constructor
     * 
     * @param \Illuminate\Filesystem\Filesystem $files     The filesystem instance
     * @param \Bgaze\Crud\Core\Crud $crud                  The Crud instance
     */
    public function __construct(Filesystem $files, Crud $crud) {
        $this->files = $files;
        $this->crud = $crud;
    }

}
