<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Exception;
use Illuminate\Support\Composer;

class BuildSeederClass extends Task
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
     * @throws Exception
     */
    public function execute()
    {
        // Populate migration stub.
        $stub = $this->populateStub('seeder');

        // Generate migration file.
        Helpers::generatePhpFile($this->file(), $stub);

        // Update autoload.
        resolve(Composer::class)->dumpAutoloads();
    }

}