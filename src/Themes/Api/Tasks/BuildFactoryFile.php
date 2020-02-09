<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;

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
     * Execute task.
     *
     * @return void
     */
    public function execute()
    {
        // Generate migration content.
        //$content = $this->compileAll('factory-content', '// TODO');

        // Write factory file.
        //$stub = $this->stub('factory');
        //$this->replace($stub, '#CONTENT', $content);
        //$this->generatePhpFile($this->file(), $stub);
    }

}