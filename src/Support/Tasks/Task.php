<?php


namespace Bgaze\Crud\Support\Tasks;


use Bgaze\Crud\Support\Crud\Crud;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Bgaze\Crud\Support\Utils\Files;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

abstract class Task
{
    use Files;

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $fs;

    /**
     * The CRUD instance.
     *
     * @var Crud
     */
    protected $crud;


    /**
     * Whether the file to interact with already exists.
     *
     * @var bool
     */
    protected $file_exists;


    /**
     * Task constructor.
     *
     * @param  Crud  $crud
     */
    public function __construct(Crud $crud)
    {
        $this->fs = resolve(Filesystem::class);
        $this->crud = $crud;
        $this->file_exists = $this->fs->exists($this->file());
    }


    /**
     * @return string
     * @throws ReflectionException
     */
    public static function title()
    {
        return ucfirst(Str::snake((new ReflectionClass(static::class))->getShortName(), ' '));
    }


    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    abstract public function file();


    /**
     * Check if something prevents the task to be executed.
     *
     * @return false|string
     */
    public function cantBeDone()
    {
        if ($this->file_exists) {
            return $this->relativePath($this->file()) . ' already exists';
        }

        return false;
    }


    /**
     * Get task's action summary into console.
     *
     * @return string
     */
    public function summarize()
    {
        $path = $this->relativePath($this->file());

        if ($this->file_exists) {
            return "<fg=red>Overwrite:</> {$path}";
        }

        return "<info>Create:</info> {$path}";
    }


    /**
     * Execute task.
     *
     * @return void
     */
    abstract public function execute();


    /**
     * Get task's effect summary into console.
     *
     * @return string
     */
    public function done()
    {
        $path = $this->relativePath($this->file());

        if ($this->file_exists) {
           return " <fg=red>Overwrited:</> {$path}";
        }

        return " <info>Created:</info> {$path}";
    }


    /**
     * Get the content of a stub file and populate it with CRUD variables.
     *
     * @param  string  $name  The name of the stub
     * @param  array  $variables  A set of variables to extend CRUD variables
     * @return string       The content of stub file
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function populateStub($name, array  $variables =  []) {
        // Check that stub exists.
        $stubs = $this->crud->getCommand()->stubs();
        if (!isset($stubs[$name])) {
            throw new Exception("Undefined stub '{$name}'.");
        }

        // Get stub content & prepare variables list.
        $stub = $this->fs->get($stubs[$name]);
        $variables = array_merge($this->crud->getVariables(), $variables);

        // Return populated stub.
        return str_replace(array_keys($variables), array_values($variables), $stub);
    }

}