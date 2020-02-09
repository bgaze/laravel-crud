<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Crud\Crud;
use Bgaze\Crud\Support\Tasks\Task;
use Illuminate\Support\Str;

class BuildMigrationClass extends Task
{
    /**
     * As it contains a timestamp, we store the file name when generated.
     *
     * @var string
     */
    protected $file;


    /**
     * Task constructor.
     *
     * @param  Crud  $crud
     */
    public function __construct(Crud $crud)
    {
        parent::__construct($crud);

        $this->crud->addVariable('MigrationClass', 'Create' . $this->crud->getPlurals()->implode('') . 'Table');

        $file = Str::snake($this->crud->MigrationClass);
        $prefix = date('Y_m_d_His');
        $this->file = database_path("migrations/{$prefix}_{$file}.php");
    }


    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return $this->file;
    }


    /**
     * Check if something prevents the task to be executed.
     *
     * @return false|string
     */
    public function cantBeDone()
    {
        if (class_exists($this->crud->MigrationClass)) {
           return "A '{$this->crud->MigrationClass}' class already exists.";
        }

        $files = $this->fs->glob(database_path("migrations/*_{$this->file}.php"));
        if (count($files) > 0) {
            return "a 'migrations/*_{$this->file}.php' already exists";
        }

        return false;
    }


    /**
     * Execute task.
     *
     * @return void
     */
    public function execute()
    {
        // Generate migration content.
        //$content = $this->compileAll('migration-content', '// TODO');

        // Write migration file.
        //$stub = $this->stub('migration');
        //$this->replace($stub, '#CONTENT', $content);
        //$this->generatePhpFile($this->file(), $stub);

        // Update autoload.
        //resolve('Illuminate\Support\Composer')->dumpAutoloads();
    }

}