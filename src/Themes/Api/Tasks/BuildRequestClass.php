<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;

class BuildRequestClass extends Task
{
    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return app_path('Http/Requests/' . $this->crud->getModel()->implode('/') . 'FormRequest.php');
    }


    /**
     * Execute task.
     *
     * @return void
     */
    public function execute()
    {
        // Generate migration content.
        //$content = $this->compileAll('request-rules', '// TODO');

        // Write migration file.
        //$stub = $this->stub('request');
        //$this->replace($stub, '#CONTENT', $content);
        //$this->generatePhpFile($this->file(), $stub);

    }

}