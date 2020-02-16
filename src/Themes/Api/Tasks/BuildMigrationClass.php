<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Crud\Crud;
use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Files;
use Bgaze\Crud\Themes\Api\Compilers\MigrationContent;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

class BuildMigrationClass extends Task
{
    use Files;

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

        $this->crud->addVariables([
            'MigrationClass' => 'Create' . $this->crud->getPlurals()->implode('') . 'Table',
            'TableName' => Str::snake($this->crud->getPlurals()->implode(''))
        ]);

        $file = Str::snake($this->crud->MigrationClass);
        $files = $this->fs->glob(database_path("migrations/*_{$file}.php"));
        if(count($files) > 0){
            $this->file = $files[0];
            $this->file_exists = true;
        } else {
            $prefix = date('Y_m_d_His');
            $this->file = database_path("migrations/{$prefix}_{$file}.php");
        }
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
    public function cantBeDone()
    {
        if (class_exists($this->crud->MigrationClass)) {
            return "A '{$this->crud->MigrationClass}' class already exists.";
        }

        $file = Str::snake($this->crud->MigrationClass);
        $files = $this->fs->glob(database_path("migrations/*_{$file}.php"));
        if (count($files) > 0) {
            return "a 'migrations/[...]_{$file}.php' already exists";
        }

        return false;
    }
*/


    protected function getContent()
    {
        $compiler = new MigrationContent($this->crud);
        return $compiler->compile('// TODO');
    }


    /**
     * Execute task.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function execute()
    {
        // Populate migration stub.
        $stub = $this->populateStub('migration', [
            '#CONTENT' => $this->getContent()
        ]);

        // Generate migration file.
        $this->generatePhpFile($this->file(), $stub);

        // Update autoload.
        resolve('Illuminate\Support\Composer')->dumpAutoloads();
    }

}