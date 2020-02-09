<?php

namespace Bgaze\Crud\Support\Crud;

use Bgaze\Crud\Support\Definitions;
use Bgaze\Crud\Support\Utils\SignedInput;
use Exception;
use Illuminate\Support\Str;

/**
 * A content entry of a CRUD (entry or index).
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Entry extends SignedInput
{

    /**
     * The unique name of the entry.
     *
     * @var string
     */
    protected $name;

    /**
     * The label to use for the entry.
     *
     * @var string
     */
    protected $label;


    /**
     * The class constructor.
     *
     * @param  string  $type  The entry type.
     * @param  string  $data  Options and arguments
     * @throws Exception
     */
    public function __construct($type, $data)
    {
        // Instantiate entry.
        parent::__construct($type . ' ' . Definitions::signature($type));

        // Set & validate user input.
        $this->ask($data);
        $this->validate(Definitions::VALIDATION);

        // Set entry name.
        $this->setName();

        // Set entry label.
        $this->setLabel();
    }


    /**
     * Generate the label of the content.
     *
     * @return string
     */
    protected function setLabel()
    {
        $this->label = ucfirst(str_replace('_', ' ', Str::snake($this->name)));
        return $this;
    }


    /**
     * Generate the unique name of the content.
     *
     * @return string
     * @throws Exception
     */
    protected function setName()
    {
        if ($this->isIndex()) {
            $columns = $this->argument('columns');
            sort($columns);
            $this->name = 'index:' . implode(',', $columns);
            return $this;
        }

        if (in_array($this->command(), ['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz', 'rememberToken'])) {
            $this->name = $this->command();
            return $this;
        }

        $this->name = $this->argument('column');
        return $this;
    }


    /**
     * Check if the entry is an index.
     *
     * @return boolean
     */
    public function isIndex()
    {
        return isset(Definitions::INDEXES[$this->command()]);
    }


    /**
     * Check if the entry is an date.
     *
     * @return boolean
     */
    public function isDate()
    {
        return isset(Definitions::DATES[$this->command()]);
    }


    /**
     * The unique name of the entry.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }


    /**
     * The label to use for the entry.
     *
     * @return string
     */
    public function label()
    {
        return $this->label;
    }


    /**
     * Get the columns added to the table.
     *
     * @return array
     * @throws Exception
     */
    public function columns()
    {
        if ($this->isIndex()) {
            return [];
        }

        switch ($this->command()) {
            case 'timestamps':
            case 'timestampsTz':
                return ['created_at', 'updated_at'];
            case 'softDeletes':
            case 'softDeletesTz':
                return ['deleted_at'];
            case 'rememberToken':
                return ['remember_token'];
            default:
                return (array) $this->argument('column');
        }
    }


    /**
     * Get entry arguments.
     *
     * @return array
     * @throws Exception
     */
    public function arguments()
    {
        return $this->input()->getArguments();
    }


    /**
     * Get entry argument by key.
     *
     * @param  string  $key  The key of the entry
     * @return mixed
     * @throws Exception
     */
    public function argument($key)
    {
        return $this->input()->getArgument($key);
    }


    /**
     * Get entry options.
     *
     * @return array
     * @throws Exception
     */
    public function options()
    {
        return $this->input()->getOptions();
    }


    /**
     * Get entry option by key.
     *
     * @param  string  $key  The key of the entry
     * @param  mixed  $default  The default value of the entry
     * @return mixed
     * @throws Exception
     */
    public function option($key, $default = null)
    {
        if (!$this->definition()->hasOption($key) || !$this->input()->hasOption($key)) {
            return $default;
        }

        $value = $this->input()->getOption($key);

        return ($value === null) ? $default : $value;
    }

}
