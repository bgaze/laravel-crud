<?php

namespace Bgaze\Crud\Themes\Api\Tasks;


use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Definitions;
use Bgaze\Crud\Support\Tasks\Task;
use Bgaze\Crud\Support\Utils\Helpers;
use Bgaze\Crud\Themes\Api\Compilers\ModelAnnotations;
use Exception;
use Illuminate\Support\Str;

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
     * @throws Exception
     */
    public function execute()
    {
        // Populate migration stub.
        $stub = $this->populateStub('model', [
            '#ANNOTATIONS' => $this->annotations(),
            '#TRAITS' => $this->traits(),
            '#TIMESTAMPS' => $this->timestamps(),
            '#FILLABLES' => $this->fillables(),
            '#DATES' => $this->dates(),
            '#CASTS' => $this->casts(),
            '#METHODS' => $this->methods(),
        ]);

        // Generate migration file.
        Helpers::generatePhpFile($this->file(), $stub);
    }


    /**
     * Compile CRUD content to migration statements.
     *
     * @return string
     */
    protected function annotations()
    {
        $compiler = new ModelAnnotations($this->crud);
        return $compiler->compile('* TODO');
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
     * Compile CRUD timestamps.
     *
     * @return string
     */
    protected function timestamps()
    {
        if ($this->crud->getTimestamps()) {
            return '';
        }

        return " /**
                  * Indicates if the model should be timestamped.
                  *
                  * @var bool
                  */
                  public \$timestamps = false;";
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
            ->filter();

        $fillables = Helpers::compileArrayForPhp($fillables->all(), false, true);

        return "/**
                 * The attributes that are mass assignable.
                 *
                 * @var array
                 */
                 protected \$fillable = {$fillables};";
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
            ->map(function (Entry $entry) {
                return $entry->isDate() ? $entry->columns() : null;
            })
            ->filter()
            ->flatten();

        if ($this->crud->getSoftDeletes()) {
            $dates->prepend('deleted_at');
        }

        if ($dates->isEmpty()) {
            return '';
        }

        $dates = Helpers::compileArrayForPhp($dates->unique()->all(), false, true);

        return "/**
                 * The attributes that should be mutated to dates.
                 *
                 * @var array
                 */
                protected \$dates = {$dates};";
    }


    /**
     * Compile CRUD content to Model casts array.
     *
     * @return string
     */
    protected function casts()
    {
        $casts = $this->crud
            ->getContent(false)
            ->map(function (Entry $entry) {
                if (in_array($entry->command(), ['json', 'jsonb'])) {
                    return 'json';
                }

                return false;
            })
            ->filter();

        if ($casts->isEmpty()) {
            return '';
        }

        $casts = Helpers::compileArrayForPhp($casts->all(), true, true);

        return "/**
                 * The attributes that should be cast to native types.
                 *
                 * @var array
                 */
                protected \$casts = $casts;";
    }


    /**
     * Compile CRUD content to Model methods.
     *
     * @return string
     */
    protected function methods()
    {
        return $this->crud
            ->getContent()
            ->map(function (Entry $entry) {
                if ($entry->command() !== 'set') {
                    return false;
                }

                return $this->populateStub('partials.set-casts', [
                    'FieldSnake' => $entry->name(),
                    'FieldStudly' => Str::studly($entry->name()),
                ]);
            })
            ->filter()
            ->implode("\n");
    }

}