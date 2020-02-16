<?php


namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Definitions;
use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

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
     * @throws FileNotFoundException
     */
    public function execute()
    {
        // Populate migration stub.
        $stub = $this->populateStub('model', [
            '#USES' => $this->uses(),
            '#TRAITS' => $this->traits(),
            '#TIMESTAMPS' => $this->timestamps(),
            '#FILLABLES' => $this->fillables(),
            '#DATES' => $this->dates(),
        ]);

        // Generate migration file.
        Helpers::generatePhpFile($this->file(), $stub);
    }


    /**
     * Compile CRUD "use" statements.
     *
     * @return string
     */
    protected function uses()
    {
        $uses = $this->crud->getContent()->map(function (Entry $entry) {
            if (in_array($entry->command(), ['softDeletes', 'softDeletesTz'])) {
                return 'use Illuminate\Database\Eloquent\SoftDeletes;';
            }

            return null;
        });

        $uses->push('use Illuminate\Database\Eloquent\Model;');

        return $uses->filter()->unique()->sort()->implode("\n");
    }


    /**
     * Compile CRUD timestamps.
     *
     * @return string
     */
    protected function timestamps()
    {
        return $this->crud->getTimestamps() ? '' : 'public $timestamps = false;';
    }


    /**
     * Compile CRUD traits.
     *
     * @return string
     */
    protected function traits()
    {
        return $this->crud->getSoftDeletes() ? 'use SoftDeletes;' : '';
    }


    /**
     * Compile CRUD content to Model fillables array.
     *
     * @return string
     */
    protected function fillables()
    {
        $exclude = ['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz'];

        $fillables = $this->crud->getContent(false)
            ->map(function (Entry $entry) use ($exclude) {
                return in_array($entry->name(), $exclude) ? false : $entry->columns();
            })
            ->flatten()
            ->filter()
            ->toArray();

        return 'protected $fillable = ' . Helpers::compileArrayForPhp($fillables) . ';';
    }


    /**
     * Compile CRUD content to Model dates array.
     *
     * @return string
     */
    protected function dates()
    {
        $dates = $this->crud
            ->getContent(false)
            ->filter(function (Entry $entry) {
                return $entry->isDate();
            })
            ->keys();

        if ($this->crud->getSoftDeletes()) {
            $dates->prepend('deleted_at');
        }

        return 'protected $dates = ' . Helpers::compileArrayForPhp($dates->unique()->toArray()) . ';';
    }

}