<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Bgaze\Crud\Themes\Api\Compilers\RequestRules;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

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
     * Compile CRUD content to migration statements.
     *
     * @return string
     */
    protected function getContent()
    {
        $compiler = new RequestRules($this->crud);
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
        $stub = $this->populateStub('request', [
            '#CONTENT' => $this->getContent()
        ]);

        // Generate migration file.
        Helpers::generatePhpFile($this->file(), $stub);
    }

}