<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;

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
     */
    public function execute()
    {
        //$this->generatePhpFile($this->file(), $this->stub('controller'));
    }
}