<?php

namespace Bgaze\Crud\Support\Crud;

use Bgaze\Crud\Support\Theme\Command;
use Exception;
use Illuminate\Support\Collection;

class Crud
{
    /**
     * The console command instance.
     *
     * @var Command
     */
    private $command;


    /**
     * The Model parents and name.
     * Example : ['MyGrandParent', 'MyParent', 'MyModel']
     *
     * @var Collection
     */
    protected $model;

    /**
     * The Model's parents and the plural version of its name.
     * Example : ['MyGrandParent', 'MyParent', 'MyModels']
     *
     * @var Collection
     */
    protected $plural;

    /**
     * The plural version of Model's parents and name.
     * Example : ['MyGrandParents', 'MyParents', 'MyModels']
     *
     * @var Collection
     */
    protected $plurals;

    /**
     * The layout to extend in generated views.
     *
     * @var boolean|string
     */
    protected $layout;

    /**
     * The timestamps to use.
     *
     * @var boolean|string
     */
    protected $timestamps;

    /**
     * The soft deletes to use.
     *
     * @var boolean|string
     */
    protected $softDeletes;

    /**
     * Model's Entry objects.
     *
     * @var Collection
     */
    protected $content;

    /**
     * The list of available variables
     *
     * @var array
     */
    protected $variables;

    /**
     * The list of tasks to execute
     *
     * @var Collection
     */
    protected $tasks;


    /**
     * CRUD constructor.
     * @param  Command  $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
        $this->content = new Collection();
        $this->timestamps = false;
        $this->softDeletes = false;
        $this->layout = false;
        $this->variables = [];
        $this->tasks = [];
    }


    /**
     * Get the console command instance.
     *
     * @return Command
     */
    public function getCommand(): Command
    {
        return $this->command;
    }


    /**
     * Get the Model parents and name.
     *
     * @return  Collection
     */
    public function getModel()
    {
        return $this->model;
    }


    /**
     * Set the Model parents and name.
     *
     * @param  Collection  $model
     *
     * @return  self
     */
    public function setModel(Collection $model)
    {
        $this->model = $model;

        return $this;
    }


    /**
     * Get the Model's parents and the plural version of its name.
     *
     * @return  Collection
     */
    public function getPlural()
    {
        return $this->plural;
    }


    /**
     * Set the Model's parents and the plural version of its name.
     *
     * @param  Collection  $plural
     *
     * @return  self
     */
    public function setPlural(Collection $plural)
    {
        $this->plural = $plural;

        return $this;
    }


    /**
     * Get the plural version of Model's parents and name.
     *
     * @return  Collection
     */
    public function getPlurals()
    {
        return $this->plurals;
    }


    /**
     * Set the plural version of Model's parents and name.
     *
     * @param  Collection  $plurals
     *
     * @return  self
     */
    public function setPlurals(Collection $plurals)
    {
        $this->plurals = $plurals;

        return $this;
    }


    /**
     * Get the layout to extend in generated views.
     *
     * @return  string
     */
    public function getLayout()
    {
        return $this->layout;
    }


    /**
     * Set the layout to extend in generated views.
     *
     * @param  boolean|string  $layout  The layout to extend in generated views.
     *
     * @return  self
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }


    /**
     * Get the timestamps to use.
     *
     * @return  boolean|string
     */
    public function getTimestamps()
    {
        return $this->timestamps;
    }


    /**
     * Set the timestamps to use.
     *
     * @param  boolean|string  $timestamps  The timestamps to use.
     *
     * @return  self
     */
    public function setTimestamps($timestamps)
    {
        $this->timestamps = $timestamps;

        return $this;
    }


    /**
     * Get the soft deletes to use.
     *
     * @return  boolean|string
     */
    public function getSoftDeletes()
    {
        return $this->softDeletes;
    }


    /**
     * Set the soft deletes to use.
     *
     * @param  boolean|string  $softDeletes  The soft deletes to use.
     *
     * @return  self
     */
    public function setSoftDeletes($softDeletes)
    {
        $this->softDeletes = $softDeletes;

        return $this;
    }


    /**
     * Get model's Entry objects.
     *
     * @param  bool  $withIndexes
     * @return  Collection
     */
    public function getContent($withIndexes = true)
    {
        if (!$withIndexes) {
            return $this->content->filter(function (Entry $entry) {
                return !$entry->isIndex();
            });
        }

        return $this->content;
    }


    /**
     * Add an Entry to CRUD content.
     *
     * @param  Entry  $entry
     * @return  self
     * @throws Exception
     */
    public function addContent(Entry $entry)
    {
        // Validate provided entry.
        $this->validateEntry($entry);

        // Add to CRUD content.
        $this->content->put($entry->name(), $entry);
        $this->reorderContent();

        // Manage timestamps & softDeletes status.
        if ($entry->name() === 'timestamps' || $entry->name() === 'timestampsTz') {
            $this->setTimestamps($entry->name());
        }
        if ($entry->name() === 'softDeletes' || $entry->name() === 'softDeletesTz') {
            $this->setSoftDeletes($entry->name());
        }

        return $this;
    }


    /**
     * Validate entry.
     *
     * @param  Entry  $entry
     * @throws Exception
     * @throws Exception
     */
    protected function validateEntry(Entry $entry)
    {
        $columns = $this->getColumns();

        if ($entry->isIndex()) {
            // Check that index doesn't already exists.
            if ($this->getContent()->has($entry->name())) {
                throw new Exception("'{$entry->name()}' index already exists.");
            }

            // Check that all selected columns exists.
            foreach ($entry->argument('columns') as $column) {
                if (!$columns->contains($entry->name())) {
                    throw new Exception("'$column' doesn't exists in entries list.");
                }
            }
        } else {
            // Check that no entry already exists.
            $intersect = $columns->intersect($entry->columns());
            if ($intersect->isNotEmpty()) {
                throw new Exception("Following column(s) already exist: " . $intersect->implode(', '));
            }
        }
    }


    /**
     * Model's table columns list.
     *
     * @return Collection
     */
    public function getColumns()
    {
        return $this->getContent(false)
            ->map(function (Entry $entry) {
                return $entry->columns();
            })
            ->prepend('id')
            ->flatten();
    }


    /**
     * Reorder the CRUD content this way :
     * - Columns in their original order except timestamps & softDeletes
     * - Then timestamps
     * - Then softDeletes
     * - Then indexes
     */
    public function reorderContent()
    {
        $index = function (Entry $entry) {
            if ($entry->isIndex()) {
                return 1;
            }

            if (in_array($entry->name(), ['softDeletes', 'softDeletesTz'])) {
                return 2;
            }

            if (in_array($entry->name(), ['timestamps', 'timestampsTz'])) {
                return 3;
            }

            return 4;
        };

        $this->content->sort(function (Entry $a, Entry $b) use ($index) {
            return ($index($a) - $index($b));
        });
    }


    /**
     * Get the list of available variables
     *
     * @return  array
     */
    public function getVariables()
    {
        return $this->variables;
    }


    /**
     * Set the CRUD variables.
     *
     * @param  array  $variables
     * @return  self
     */
    public function addVariables(array $variables)
    {
        $this->variables = array_merge($this->variables, $variables);

        krsort($this->variables);

        return $this;
    }


    /**
     * Get a CRUD variable.
     *
     * @param  string  $name
     * @return  string
     */
    public function getVariable($name)
    {
        return $this->variables[$name] ?? '';
    }


    /**
     * Get a CRUD variable.
     *
     * @param $name
     * @return  string
     */
    public function __get($name)
    {
        return $this->getVariable($name);
    }


    /**
     * Add a CRUD variable.
     *
     * @param $key
     * @param $value
     * @return  self
     */
    public function addVariable($key, $value)
    {
        $this->variables[$key] = $value;

        krsort($this->variables);

        return $this;
    }


    /**
     * @return Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }


    /**
     * Instantiate CRUD tasks
     *
     * @param  Collection  $tasks
     * @return self
     */
    public function setTasks(Collection $tasks)
    {
        $this->tasks = $tasks->map(function ($class) {
            return new $class($this);
        });

        return $this;
    }


}
