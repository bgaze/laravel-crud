<?php

namespace Bgaze\Crud\Console\Crud;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as Base;
use Illuminate\Support\Str;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Composer;
use Bgaze\Crud\Support\MigrateField;

class MigrateMakeCommand extends Base {

    use \Bgaze\Crud\Support\ConsoleHelpersTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'bgaze:crud:migration {name : The name of the migration.}
        {--path= : The location where the migration file should be created.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD migration file';

    /**
     *
     * @var \Illuminate\Database\Eloquent\Collection 
     */
    protected $columns;

    /**
     *
     * @var array 
     */
    protected $migration = [];

    /**
     * 
     */
    public function __construct(MigrationCreator $creator, Composer $composer) {
        parent::__construct($creator, $composer);

        $this->columns = collect(config('crud_dic.migrate.fields'))->map(function($v, $k) {
            $tmp = new MigrateField($k, $v['signature'], $v['description'], $v['template']);
            $tmp->setRules(isset($v['validation']) ? $v['validation'] : [], config('crud_dic.migrate.validation'));
            return $tmp;
        });
    }

    public function handle() {
        // Get migration file and table name.
        $name = Str::snake(trim($this->input->getArgument('name')));
        if (!preg_match('/^create_(\w+)_table$/', $name, $m)) {
            $this->error("Migration name must match '/^create_(\w+)_table$/' regex.");
        }
        $table = $m[1];

        // Show intro text.
        $this->intro();

        // Get columns list.
        $this->setColumns();
        var_dump($this->migration);
        die();

        // Write migration file.
        $this->writeMigration($name, $table, true);

        // Dump autoload for the entire framework to make sure that the migrations are registered by the class loaders.
        $this->composer->dumpAutoloads();
    }

    protected function setColumns() {
        $reg = '/^(' . $this->columns->keys()->implode('|') . ')(\s.*)?$/';

        $columns = $this->columns->keys()->map(function($v) {
                    return "$v ";
                })->merge(['list', 'no'])->toArray();

        while (true) {
            $question = trim($this->anticipate('Add a column ?', $columns, 'no'));

            if ($question === 'no') {
                if (empty($this->migration) && !$this->confirm("No field added to migration. Abort ?")) {
                    continue;
                }
                break;
            }

            if ($question === 'list') {
                $this->columnsHelp();
                continue;
            }

            try {
                if (!preg_match($reg, $question, $m)) {
                    throw new \Exception("Invalid input '$question'.");
                }

                $this->setColumn($m[1], isset($m[2]) ? trim($m[2]) : '');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
                continue;
            }
        }
    }

    protected function setColumn($field, $question) {
        if (!$this->columns->has($field)) {
            throw new \Exception("Undefined field '$field'.");
        }

        $column = $this->columns->get($field);

        if (empty($question)) {
            $this->info($column->getDescription());
            $this->line($column->help());
            return;
        }

        $input = $column->input($question);
        $name = $input->getArgument('column');

        if (isset($this->migration[$name])) {
            throw new \Exception("'$name' field already exists in this migration.");
        }

        $this->migration[$name] = $column->compile($input);

        $this->line('Added : ' . $this->migration[$name]);
    }

    protected function intro() {
        $this->h1("Table columns");
        $this->line("An auto-incremented <fg=green>id</> field will be automatically inserted into table.\n");
        $this->line("For available column types, enter <fg=green>list</>.\nFor a column detailed syntax, <fg=green>omit arguments and options.</>");
    }

    protected function columnsHelp() {
        $this->table(['Column name', 'Arguments', 'Options'], $this->columns->map(function ($v) {
                    return $v->help(true);
                }));
    }

}
