<?php


namespace Bgaze\Crud\Themes\Classic\Tasks;


use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Bgaze\Crud\Themes\Classic\Compilers\PrintContent;
use Exception;

class BuildIndexView extends Task
{
    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return resource_path('views/' . $this->crud->PluralsKebabSlash . "/index.blade.php");
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
        $stub = $this->populateStub('views.index', [
            '#THEAD' => $this->getHeaders(),
            '#TBODY' => $this->getBody()
        ]);

        // Generate migration file.
        Helpers::generateBladeFile($this->file(), $stub);
    }


    /**
     * Compile index table header.
     *
     * @return string
     */
    protected function getHeaders()
    {
        $compiler = new PrintContent($this->crud, 'partials.index-head');
        return $compiler->compile('<!-- TODO -->');
    }


    /**
     * Compile index table body.
     *
     * @return string
     */
    protected function getBody()
    {
        $compiler = new PrintContent($this->crud, 'partials.index-body');
        return $compiler->compile('<!-- TODO -->');
    }

}