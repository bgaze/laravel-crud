<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Definitions;
use Bgaze\Crud\Support\Tasks\Task;

class BuildModelClass extends Task
{
    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return app_path(trim(Definitions::modelsDirectory() . '/' . $this->crud->getModel()->implode('/') . '.php', '/'));
    }


    /**
     * Execute task.
     *
     * @return void
     */
    public function execute()
    {
        // TODO: Implement execute() method.
    }

}