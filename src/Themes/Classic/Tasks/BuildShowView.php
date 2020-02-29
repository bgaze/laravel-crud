<?php


namespace Bgaze\Crud\Themes\Classic\Tasks;


use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Bgaze\Crud\Themes\Classic\Compilers\PrintContent;
use Exception;

class BuildShowView extends Task
{
    /**
     * The file that the task interact with.
     *
     * @return string The absolute path of the file
     */
    public function file()
    {
        return resource_path('views/' . $this->crud->PluralsKebabSlash . "/show.blade.php");
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
        $stub = $this->populateStub('views.show', [
            '#CONTENT' => $this->getContent(),
        ]);

        // Generate migration file.
        Helpers::generatePhpFile($this->file(), $stub);
    }


    /**
     * Compile view content.
     *
     * @return string
     */
    protected function getContent()
    {
        $compiler = new PrintContent($this->crud, 'partials.show-group');
        return $compiler->compile('<!-- TODO -->');
    }

}