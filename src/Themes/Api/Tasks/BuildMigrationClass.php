<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Crud\Crud;
use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Bgaze\Crud\Themes\Api\Compilers\MigrationContent;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Composer;
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

        $file = Str::snake($this->crud->MigrationClass);
        $files = $this->fs->glob(database_path("migrations/*_{$file}.php"));

        if (count($files) > 0) {
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
     * Compile CRUD content to migration statements.
     *
     * @return string
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
        Helpers::generatePhpFile($this->file(), $stub);

        // Update autoload.
        resolve(Composer::class)->dumpAutoloads();
    }

}