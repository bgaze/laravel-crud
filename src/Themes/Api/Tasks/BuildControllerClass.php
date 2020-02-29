<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Exception;

class BuildControllerClass extends Task
{
    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return app_path('Http/Controllers/' . $this->crud->getModel()->implode('/') . 'Controller.php');
    }


    /**
     * Execute task.
     *
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        // Populate stub.
        $stub = $this->populateStub('controller');

        // Generate file.
        Helpers::generatePhpFile($this->file(), $stub);
    }
}