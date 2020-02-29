<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Bgaze\Crud\Themes\Api\Compilers\FactoryContent;
use Exception;

class BuildFactoryFile extends Task
{
    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return database_path('factories/' . $this->crud->getPlural()->implode('/') . 'Factory.php');
    }

    /**
     * Compile CRUD content to factory statements.
     *
     * @return string
     */
    protected function getContent()
    {
        $compiler = new FactoryContent($this->crud);
        return $compiler->compile('// TODO');
    }


    /**
     * Execute task.
     *
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        // Populate migration stub.
        $stub = $this->populateStub('factory', [
            '#CONTENT' => $this->getContent()
        ]);

        // Generate migration file.
        Helpers::generatePhpFile($this->file(), $stub);
    }

}