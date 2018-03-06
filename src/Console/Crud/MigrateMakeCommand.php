<?php

namespace Bgaze\Crud\Console\Crud;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as Base;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Composer;
use Bgaze\Crud\Support\MigrateField;

class MigrateMakeCommand extends Base {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'bgaze:crud:migration {name : The name of the migration.}
        {--create= : The table to be created.}
        {--table= : The table to migrate.}
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
        //parent::handle();
        $this->getColumns();
    }

    protected function getColumns() {
        $reg = '/^(' . $this->columns->keys()->implode('|') . ')(\s.*)?$/';

        $this->warn('Table columns');
        $this->line('To see available column types, enter "list".');
        $this->line('To see detailed syntax for a column, omit arguments and options.');

        while (true) {
            $question = $this->anticipate('Add a column ?', $this->columns->keys()->toArray(), 'no');

            if ($question === 'no') {
                break;
            }

            if ($question === 'list') {
                $this->table(['Column name', 'Arguments', 'Options'], $this->columns->map(function ($v) {
                            return $v->help(true);
                        }));
                continue;
            }

            try {
                if (!preg_match($reg, $question, $m)) {
                    throw new \Exception("Invalid input '$question'.");
                }

                $this->migration[] = $this->getColumn($m[1], isset($m[2]) ? trim($m[2]) : '');
                var_dump($this->migration);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
                continue;
            }
        }
    }

    protected function getColumn($field, $question) {
        if (!$this->columns->has($field)) {
            throw new \Exception("Undefined field '$field'.");
        }

        $column = $this->columns->get($field);

        if (empty($question)) {
            $this->info($column->getDescription());
            $this->line($column->help());
            return;
        }

        $this->migration[] = $column->input($question);
    }

}
