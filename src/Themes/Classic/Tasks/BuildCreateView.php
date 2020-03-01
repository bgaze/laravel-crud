<?php


namespace Bgaze\Crud\Themes\Classic\Tasks;


use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Bgaze\Crud\Themes\Classic\Compilers\FormContent;
use Exception;

class BuildCreateView extends Task
{
    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return resource_path('views/' . $this->crud->PluralsKebabSlash . "/create.blade.php");
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
        $stub = $this->populateStub('views.create', [
            '#CONTENT' => $this->getContent(),
        ]);

        // Generate migration file.
        Helpers::generateBladeFile($this->file(), $stub);
    }


    /**
     * Compile view content.
     *
     * @return string
     */
    protected function getContent()
    {
        $compiler = new FormContent($this->crud);
        return $compiler->compile('<!-- TODO -->');
    }

}