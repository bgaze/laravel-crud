<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;

class BuildSeedsClass extends Task
{

    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return database_path('seeds/' . $this->crud->getPlural()->implode('') . 'TableSeeder.php');
    }


    /**
     * Execute task.
     *
     * @return void
     */
    public function execute()
    {
        // Write migration file.
        //$this->generatePhpFile($this->file(), $this->stub('seeds'));

        // Update autoload.
        //resolve('Illuminate\Support\Composer')->dumpAutoloads();
    }

}