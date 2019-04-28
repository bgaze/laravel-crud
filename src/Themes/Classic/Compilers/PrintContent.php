<?php

namespace Bgaze\Crud\Themes\Classic\Compilers;

use Illuminate\Filesystem\Filesystem;
use Bgaze\Crud\Core\Crud;
use Bgaze\Crud\Core\Compiler;
use Bgaze\Crud\Core\Entry;

/**
 * Description of IndexTbody
 *
 * @author bgaze
 */
class PrintContent extends Compiler {

    /**
     * The stub used to generate rows
     * 
     * @var string 
     */
    protected $stub;

    /**
     * The class constructor
     * 
     * @param \Illuminate\Filesystem\Filesystem $files     The filesystem instance
     * @param \Bgaze\Crud\Core\Crud $crud                  The Crud instance
     * @param string $stub                                 The stub used to generate rows
     */
    public function __construct(Filesystem $files, Crud $crud, $stub) {
        parent::__construct($files, $crud);
        $this->stub = $stub;
    }

    /**
     * Generate a table cell 
     * 
     * @param string $label
     * @param string $name
     * @return string
     */
    protected function printGroup($label, $name) {
        $stub = $this->stub($this->stub);
        $this->replace($stub, 'EntryLabel', $label)->replace($stub, 'EntryName', $name);
        return $stub;
    }

    /**
     * Get the default compilation function for an entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The compiled entry
     */
    public function compileDefault(Entry $entry) {
        if ($entry->isIndex()) {
            return null;
        }

        return $this->printGroup($entry->label(), $entry->name());
    }

    /**
     * Compile a rememberToken entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function rememberToken(Entry $entry) {
        return null;
    }

    /**
     * Compile a softDeletes entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletes(Entry $entry) {
        return null;
    }

    /**
     * Compile a softDeletesTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTz(Entry $entry) {
        return $this->softDeletes($entry);
    }

    /**
     * Compile a timestamps entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestamps(Entry $entry) {
        return $this->printGroup('Created at', 'created_at') . "\n" . $this->printGroup('Updated at', 'updated_at');
    }

    /**
     * Compile a timestampsTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTz(Entry $entry) {
        return $this->timestamps($entry);
    }

}
