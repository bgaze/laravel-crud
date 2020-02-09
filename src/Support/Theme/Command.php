<?php

namespace Bgaze\Crud\Support\Theme;

use Bgaze\Crud\Support\Crud\Crud;
use Bgaze\Crud\Support\Definitions;
use Bgaze\Crud\Support\Tasks\Task;
use Exception;
use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\TableSeparator;

/**
 * The CRUD generator command.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
abstract class Command extends BaseCommand
{

    /**
     * The CRUD instance.
     *
     * @var Crud
     */
    protected $crud;


    /**
     * The command constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * The stubs available in the CRUD theme.
     *
     * @return array Name as key, absolute path as value.
     */
    abstract public function stubs();


    /**
     * The tasks available in the CRUD theme.
     *
     * @return array Name as key, full class name as value.
     */
    abstract public function tasks();


    /**
     * Register custom styles into command output.
     *
     * @return Crud
     */
    abstract protected function compose();


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->crud = new Crud($this);

            $this->setCustomStyles();

            $this->h1("Welcome to CRUD generator");

            $this->h2('Configuration');

            $this->compose();

            $this->h2('Generation');

            $this->summarize();

            $built = $this->build();

            $this->end($built);
        } catch (Exception $e) {
            $this->nl();
            $this->line($this->getHelperSet()->get('formatter')->formatBlock($e->getMessage(), 'error', true));
            $this->nl();
            exit(1);
        }
    }


    /**
     * Summarize the command actions.
     *
     * @return void
     */
    protected function summarize()
    {
        if (!$this->option('no-interaction')) {
            $tasks = $this->crud->getTasks()->map(function (Task $task) {
                return ' - ' . $task->summarize();
            });
            $this->warn(' Following action(s) will be executed:');
            $this->line($tasks->implode("\n"));
            $this->nl();
        }
    }


    /**
     * Register custom styles into command output.
     *
     * @return bool
     */
    protected function build()
    {
        // If interactions, ask for user confirmation.
        if (!$this->option('no-interaction') && !$this->confirm('Proceed?', true)) {
            return false;
        }

        // Execute builders showing an action summary.
        $this->crud->getTasks()->each(function (Task $task) {
            $task->execute();
            $this->line($task->done());
        });
        $this->nl();

        return true;
    }


    /**
     * Show exit message based on build success.
     *
     * @param  bool  $built
     */
    protected function end($built)
    {
        if ($built) {
            $this->comment(' CRUD generated successfully.');
        } else {
            $this->warn(' CRUD generation aborted.');
        }
        $this->nl();
    }


    /**
     * Helper to access theme's config.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function config($key, $default)
    {
        $namespace = str_replace(':', '-', $this->name);
        return config("{$namespace}.{$key}", $default);
    }


    /**
     * Get CRUD instance.
     *
     * @return Crud
     */
    public function getCrud()
    {
        return $this->crud;
    }


    /**
     * Register custom styles into command output.
     *
     * @return void
     */
    protected function setCustomStyles()
    {
        $this->output->getFormatter()->setStyle('h1', new OutputFormatterStyle('white', 'blue'));
        $this->output->getFormatter()->setStyle('h2', new OutputFormatterStyle('blue', null, ['bold']));
    }


    protected function fail(Exception $e)
    {

    }


    /**
     * Display a level 1 title.
     *
     * @param  string  $text  The text to display
     * @param  boolean  $test  Nothing is displayed if test fails
     */
    public function h1($text, $test = true)
    {
        if ($test) {
            $this->nl();
            $this->line("<h1>" . str_repeat(" ", 80) . "</h1>");
            $this->line("<h1>" . str_pad(strtoupper(" $text"), 80) . "</h1>");
            $this->line("<h1>" . str_repeat(" ", 80) . "</h1>");
            $this->nl();
        }
    }


    /**
     * Display a level 2 title.
     *
     * @param  string  $text
     * @param  boolean  $test  Nothing is displayed if test fails
     */
    public function h2($text, $test = true)
    {
        if ($test) {
            $this->line(" <h2>" . strtoupper($text) . "</h2>");
            $this->nl();
        }
    }


    /**
     * Displays a new line.
     *
     * @param  boolean  $test  Nothing is displayed if test fails
     */
    public function nl($test = true)
    {
        if (!$this->option('quiet') && $test) {
            echo "\n";
        }
    }


    /**
     * Display a definition.
     *
     * @param  string  $dt  The label of definition
     * @param  string  $dd  The value of definition
     * @param  boolean  $test  Nothing is displayed if test fails
     */
    public function dl($dt, $dd, $test = true)
    {
        if ($test) {
            $this->line(" <info>{$dt}:</info> {$dd}");
        }
    }


    /**
     * Display help for a entry type.
     *
     * @param  string  $name  The name of the entry.
     * @throws Exception
     */
    public function showEntryHelp($name)
    {
        $entry = Definitions::signature($name, true);
        $this->line("   <fg=yellow>Add a {$name} entry to the CRUD.</>");

        if (empty($entry['arguments']) && empty($entry['options'])) {
            $this->line("   This entry has neither argument nor option.");
        } else {
            $arguments = empty($entry['arguments']) ? '' : " <info>{$entry['arguments']}</info>";
            $options = empty($entry['options']) ? '' : " <fg=cyan>{$entry['options']}</>";
            $this->line("   Signature:  {$arguments}{$options}");
        }

        $this->nl();
    }


    /**
     * Display a help table for all available entries type.
     * @throws Exception
     */
    public function showEntriesHelp()
    {
        $rows = [];

        foreach (array_keys(Definitions::COLUMNS) as $name) {
            $rows[] = Definitions::signature($name, true);
        }

        $rows[] = new TableSeparator();

        foreach (array_keys(Definitions::INDEXES) as $name) {
            $rows[] = Definitions::signature($name, true);
        }

        $this->table(['Command', 'Arguments', 'Options'], $rows);
    }
}
