<?php

namespace Bgaze\Crud\Core;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Bgaze\Crud\Core\CompilerTrait;
use Bgaze\Crud\Core\Command;

/**
 * Builds a file using a CRUD instance
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
abstract class Builder {

    use CompilerTrait;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The CRUD command instance.
     * 
     * @var \Bgaze\Crud\Core\Command 
     */
    protected $command;

    /**
     * The class constructor
     * 
     * @param \Illuminate\Filesystem\Filesystem $files     The filesystem instance
     * @param \Bgaze\Crud\Core\Command $command            The command instance
     */
    public function __construct(Filesystem $files, Command $command) {
        $this->files = $files;
        $this->command = $command;
        $this->crud = $this->command->getCrud();
    }

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    abstract public function file();

    /**
     * Build the file.
     */
    abstract public function build();

    /**
     * Get builder slug.
     * 
     * @return string
     */
    public static function slug() {
        $class = new \ReflectionClass(static::class);
        return Str::kebab($class->getShortName());
    }

    /**
     * Get human friendly builder name.
     * 
     * @return string
     */
    public static function name() {
        return ucfirst(str_replace('-', ' ', static::slug()));
    }

    /**
     * Check if nothing prevent the builder execution.
     * 
     * By default this function checks that the builder target doesn't already exists
     * 
     * @return false|string     An error message, false otherwise
     */
    public function cannotBuild() {
        if ($this->files->exists($this->file())) {
            return $this->relativePath($this->file()) . ' already exists';
        }

        return false;
    }

    /**
     * Print builder's action summary into console.
     */
    public function summarize() {
        $this->command->dl(' ' . static::name() . ' creation', $this->relativePath($this->file()));
    }

    /**
     * Print builder's effect summary into console.
     */
    public function done() {
        $this->command->dl(' Created ' . static::name(), $this->relativePath($this->file()));
    }

    /**
     * Instanciate a CRUD compiler
     * 
     * @param string $slug  The key in compilers list
     * @return \Bgaze\Crud\Core\Compiler
     */
    public function compiler($slug) {
        $compilers = $this->crud::compilers();
        return new $compilers[$slug]($this->files, $this->crud);
    }

    /**
     * Run a compiler against all CRUD entries.
     * 
     * @param string $compilerSlug  The key of the compiler into CRUD's compilers array
     * @param string $onEmpty       A replacement value if result is empty
     * @return string
     */
    protected function compileAll($compilerSlug, $onEmpty = '') {
        $compiler = $this->compiler($compilerSlug);
        $content = $this->crud->content()
                ->map(function(Entry $entry) use($compiler) {
                    return $compiler->compile($entry);
                })
                ->filter()
                ->implode("\n");

        if (empty($content)) {
            return $onEmpty;
        }

        return $content;
    }

}
