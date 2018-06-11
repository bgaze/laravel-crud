<?php

namespace Bgaze\Crud\Core;

use Bgaze\Crud\Core\Crud;

/**
 * Builds a file using a CRUD instance
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
abstract class Builder {

    /**
     * The CRUD instance.
     * 
     * @var \Bgaze\Crud\Core\Crud 
     */
    protected $crud;

    /**
     * The class constructor
     * 
     * @param Crud $crud
     */
    function __construct(Crud $crud) {
        $this->crud = $crud;
    }

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    abstract public function file();

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    abstract public function build();

    /**
     * Remove base_path() from a path string.
     * 
     * @param string $path  The path of the file
     * @return string       The relative path of the file
     */
    protected function relativePath($path) {
        return str_replace(base_path() . '/', '', $path);
    }

    /**
     * Prepare value for PHP generation depending on it's type.
     * 
     * @param mixed $value
     * @return string
     */
    protected function compileValueForPhp($value) {
        if (is_array($value)) {
            return '[' . collect($value)->map(function($v) {
                        return $this->compileValueForPhp($v);
                    })->implode(', ') . ']';
        }

        if ($value === true || $value === 'true') {
            return 'true';
        }

        if ($value === false || $value === 'false') {
            return 'false';
        }

        if ($value === null || $value === 'null') {
            return 'null';
        }

        if (!is_numeric($value)) {
            return "'" . addslashes($value) . "'";
        }

        return $value;
    }

    /**
     * Replace a variable name in a stub string.
     * 
     * @param string $stub          The stub content string
     * @param string $name          The variable name
     * @param string|false $value   The value to use. If false, the '$this->get{$name}()' method is called.
     * @return $this
     */
    protected function replace(&$stub, $name, $value = false) {
        if ($value === false) {
            $value = $this->{'get' . $name}();
        }

        $stub = str_replace($name, $value, $stub);

        return $this;
    }

    /**
     * Populate a stub file content and returns resulting string.
     * 
     * Any Crud method starting with "get" is automatically use as replacement.
     * 
     * @param string $stub          The stub file name (dotted syntax)
     * @param callable $replace     A callback to do custom replacements
     * @return string
     */
    protected function populateStub($name) {
        // Get stub content.
        $stub = $this->crud->stub($name);

        // Replace common variables.
        foreach ($this->crud->variables() as $var) {
            $this->replace($stub, $var);
        }

        return $stub;
    }

    /**
     * Generate a file using a stub file.
     * 
     * @param string $path The path of the file relative to base_path()
     * @param string $content The content of the file
     * @return string The relative path of the file
     */
    protected function generateFile($path, $content) {
        // Prepare file's pathes.
        $relativePath = $this->relativePath($path);
        $absolutePath = base_path($relativePath);

        // Ensure the file doesn't already exists.
        if ($this->crud->files->exists($absolutePath)) {
            throw new \Exception("A '{$relativePath}' file already exists.");
        }

        // Create output dir if necessary.
        if (!$this->crud->files->isDirectory(dirname($absolutePath))) {
            $this->files->makeDirectory(dirname($absolutePath), 0777, true, true);
        }

        // Create file.
        $this->crud->files->put($absolutePath, $content);

        // Return file path.
        return $relativePath;
    }

    /**
     * Generate a file using a stub file then fix it using PHP-CS-Fixer.
     * 
     * @param string $path      The path of the file relative to base_path()
     * @param string $content   The content of the file
     * @return string           The relative path of the file
     */
    protected function generatePhpFile($path, $content) {
        // Generate file.
        $relativePath = $this->generateFile($path, $content);

        // Fix it with PhpCsFixer.
        php_cs_fixer($relativePath, ['--quiet' => true]);

        // Return file path.
        return $relativePath;
    }

    /**
     * Fix HTML content using Tidy, then generate the file.
     * 
     * @param string $path      The path of the file relative to base_path()
     * @param string $content   The content of the file
     * @return string           The relative path of the file
     */
    protected function generateHtmlFile($path, $content) {
        // Fix HTML.
        $tidy = new Tidy();
        $tidy->parseString($content, config('crud.tidy'));
        $tidy->cleanRepair();

        // Report Tidy errors.
        if ($tidy->errorBuffer) {
            throw new \Exception("Tidy fails to process HTML : \n" . explode("\n", $tidy->errorBuffer));
        }

        // Generate file.
        return $this->generateFile($path, $tidy);
    }

    /**
     * Check that the file to generate doesn't exists.
     * 
     * @return false|string The error message if file exists, false otherwise
     */
    public function fileExists() {
        if ($this->crud->files->exists($this->file())) {
            $path = $this->relativePath($this->file());
            return "A '{$path}' file already exists.";
        }

        return false;
    }

}
