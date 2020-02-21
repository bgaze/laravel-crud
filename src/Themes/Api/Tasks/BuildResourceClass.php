<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class BuildResourceClass extends Task
{
    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return app_path('Http/Resources/' . $this->crud->getModel()->implode('/') . 'Resource.php');
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
        $stub = $this->populateStub('resource', [
            '[/*CONTENT*/]' => $this->content()
        ]);

        // Generate migration file.
        Helpers::generatePhpFile($this->file(), $stub);
    }


    /**
     * Compile the file content.
     *
     * @return string
     */
    protected function content()
    {
        $columns = $this->crud->getColumns();

        $content = $columns
            ->map(function ($column) {
                return "'{$column}' => \$this->{$column},";
            })
            ->implode("\n");

        return "[\n{$content}\n]";
    }
}