<?php

namespace Bgaze\Crud\Core;

use Illuminate\Support\Str;
use Bgaze\Crud\Core\Model;
use Bgaze\Crud\Core\Entry;

/**
 * The core class of the CRUD package
 * 
 * It manages variables and stub to use to generate files.
 * 
 * It can be extended to create custom Crud theme.
 * 
 * <b>Important :</b> 
 * 
 * In this class, getXXX method name pattern is reserved for CRUD variables.<br/>
 * Any method starting with 'get' MUST NOT have any required argument, MUST return a stringable value, 
 * and WILL be used as replacement into stubs.
 * 
 * Exemple :
 * 
 * If the method 'getMyVariableName' exists, then it's return value will be used 
 * to replace any occurence of 'MyVariableName' into stubs.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
abstract class Crud extends Model {

    /**
     * The layout to extend in generated views.
     *
     * @var string
     */
    protected $layout;

    /**
     * The timestamps to use.
     *
     * @var boolean|string 
     */
    protected $timestamps = false;

    /**
     * The soft deletes to use.
     *
     * @var boolean|string 
     */
    protected $softDeletes = false;

    /**
     * Model's Entry objects.
     *
     * @var \Illuminate\Support\Collection 
     */
    protected $content;

    /**
     * The constructor of the class.
     *
     * @return void
     */
    public function __construct($model) {
        // Init CRUD content.
        $this->content = collect();

        // Init model names.
        parent::__construct($model);

        // Init layout.
        $this->setLayout();
    }

    # THEME IDENTITY

    /**
     * The unique name of the CRUD theme.
     * 
     * @return string
     */
    abstract static public function name();

    /**
     * The description the CRUD theme.
     * 
     * @return string
     */
    abstract static public function description();

    /**
     * The stubs availables in the CRUD theme.
     * 
     * @return array Name as key, absolute path as value.
     */
    abstract static public function stubs();

    /**
     * The builders availables in the CRUD theme.
     * 
     * @return array Name as key, full class name as value.
     */
    abstract static public function builders();

    /**
     * The views directory.
     * 
     * This function should return the theme views location, or false if the theme has no views.
     * Any "Views" directory at the theme root will be used by default.
     * 
     * If defined, theme views will be registred and made publishable.
     * 
     * @return string|false
     */
    static public function views() {
        $dir = dirname((new \ReflectionClass(static::class))->getFileName()) . '/Views';
        return is_dir($dir) ? $dir : false;
    }

    /**
     * The views namespace.
     * 
     * It is used to publish and register Theme's views.
     * 
     * @return string
     */
    static public function viewsNamespace() {
        return static::views() ? str_replace(':', '-', static::name()) : false;
    }

    /**
     * The Theme base layout.
     * 
     * The default layout to extend in views.
     * 
     * @return string
     */
    static public function layout() {
        return static::views() ? static::viewsNamespace() . '::layout' : false;
    }

    # CONFIGURATION

    /**
     * Set default layout for CRUD's views.
     * 
     * @param type $value
     * @return void
     */
    public function setLayout($value = false) {
        $this->layout = $value ? $value : static::layout();
    }

    /**
     * Add a new content to CRUD.
     * 
     * @param string $type The type of the content
     * @param string $data The user parameters (signed input)
     * @throws \Exception
     * @return void
     */
    public function add($type, $data) {
        $entry = new Entry($type, $data);
        $columns = $this->columns();

        // If index
        if ($entry->isIndex()) {
            // Check that it doesn't already exists.
            if ($this->content()->has($entry->name())) {
                throw new \Exception("'{$entry->name()}' index already exists.");
            }

            // Check that all selected columns exists.
            foreach ($entry->input()->getArgument('columns') as $column) {
                if (!$columns->contains($entry->name())) {
                    throw new \Exception("'$column' doesn't exists in entries list.");
                }
            }
        }

        // Otherwise check that no entry alreay exists.
        $intersect = $columns->intersect($entry->columns());
        if ($intersect->isNotEmpty()) {
            throw new \Exception("Following columns already exist: " . $intersect->implode(', '));
        }

        // Add to entries list.
        $this->content()->put($entry->name(), $entry);

        // Manage timestamps & softDeletes status.
        if ($entry->name() === 'timestamps' || $entry->name() === 'timestampsTz') {
            $this->timestamps = $entry->name();
        }
        if ($entry->name() === 'softDeletes' || $entry->name() === 'softDeletesTz') {
            $this->softDeletes = $entry->name();
        }
    }

    /**
     * Reorder the CRUD content this way :
     * - Relations first
     * - Then columns except timestamps & softDeletes
     * - Then timestamps
     * - Then softDeletes
     * - Then indexes
     */
    public function reorderContent() {
        $content = collect();

        // Relations first.
        $this->content->filter(function(Entry $entry, $key) {
            return $entry->isRelation();
        })->each(function(Entry $entry, $key) use($content) {
            $content->put($key, $entry);
        });

        // Then columns except timestamps & softDeletes.
        $this->content->filter(function(Entry $entry) {
            if (in_array($entry->name(), ['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz'])) {
                return false;
            }
            return (!$entry->isRelation() && !$entry->isIndex());
        })->each(function(Entry $entry, $key) use($content) {
            $content->put($key, $entry);
        });

        // Then timestamps.
        $this->content->filter(function(Entry $entry) {
            return in_array($entry->name(), ['timestamps', 'timestampsTz']);
        })->each(function(Entry $entry, $key) use($content) {
            $content->put($key, $entry);
        });

        // Then softDeletes.
        $this->content->filter(function(Entry $entry) {
            return in_array($entry->name(), ['softDeletes', 'softDeletesTz']);
        })->each(function(Entry $entry, $key) use($content) {
            $content->put($key, $entry);
        });

        // Then indexes.
        $this->content->filter(function(Entry $entry) {
            return $entry->isIndex();
        })->each(function(Entry $entry, $key) use($content) {
            $content->put($key, $entry);
        });

        // Update CRUD content.
        $this->content = $content;
    }

    # GETTERS

    /**
     * Get a list of variables present in the class (based on existing methods starting with 'get').
     *
     * @return array
     */
    public function variables() {
        $variables = [];

        foreach (get_class_methods($this) as $method) {
            if (substr($method, 0, 3) === 'get') {
                $variables[] = substr($method, 3);
            }
        }

        rsort($variables);

        return $variables;
    }

    /**
     * Models sub-directory, based on global configuration.
     * 
     * @return string
     */
    public function modelsSubDirectory() {
        $dir = config('crud.models-directory', false);

        if ($dir === true) {
            return 'Models';
        }

        if ($dir && !empty($dir)) {
            return $dir;
        }

        return '';
    }

    /**
     * The timestamps to use.
     *
     * @var boolean|string 
     */
    public function timestamps() {
        return $this->timestamps;
    }

    /**
     * The soft deletes to use.
     *
     * @var boolean|string 
     */
    public function softDeletes() {
        return $this->softDeletes;
    }

    /**
     * Model's content (entries & indexes).
     *
     * @var \Illuminate\Support\Collection 
     */
    public function content($withIndexes = true) {
        if (!$withIndexes) {
            return $this->content->filter(function(Entry $entry) {
                        return !$entry->isIndex();
                    });
        }

        return $this->content;
    }

    /**
     * Model's table columns list.
     *
     * @var \Illuminate\Support\Collection 
     */
    public function columns() {
        return $this->content(false)
                        ->map(function(Entry $entry) {
                            return $entry->columns();
                        })
                        ->prepend('id')
                        ->flatten();
    }

    /**
     * Get the layout to extend in views
     * 
     * @return string
     */
    public function getViewsLayout() {
        return $this->layout;
    }

}
