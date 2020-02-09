<?php


namespace Bgaze\Crud\Support\Tasks;


use Bgaze\Crud\Support\Crud\Crud;
use Illuminate\Filesystem\Filesystem;
use Bgaze\Crud\Support\Utils\Files;
use Illuminate\Support\Str;
use ReflectionClass;

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
        if ($this->file_exists && !$this->crud->getCommand()->option('force')) {
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
            return "<warn>Overwrite:</warn> {$path}";
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
           return " <warn>Overwrited:</warn> {$path}";
        }

        return " <info>Created:</info> {$path}";
    }


}