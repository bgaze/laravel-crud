<?php

namespace Bgaze\Crud\Console\Crud;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as Base;
use Illuminate\Support\Str;
use Illuminate\Support\Composer;
use Bgaze\Crud\Support\Migration\MigrationCreator;

class MigrateMakeCommand extends Base {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'bgaze:crud:migration {name : The name of the migration.}
        {--fields=* : The PHP lines of your migration body (one string by row).}
        {--path= : The location where the migration file should be created.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD migration file';

    /**
     * Create a new migration install command instance.
     *
     * @param  \Bgaze\Crud\Support\MigrationCreator  $creator
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(MigrationCreator $creator, Composer $composer) {
        parent::__construct($creator, $composer);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {

        $name = Str::snake(trim($this->input->getArgument('name')));
        if (!preg_match('/^create_(\w+)_table$/', $name, $m)) {
            throw new \Exception("Migration name must match /^create_(\w+)_table$/");
        }

        $this->writeMigration($name, $m[1], true);

        $this->composer->dumpAutoloads();
    }

    /**
     * Write the migration file to disk.
     *
     * @param  string  $name
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    protected function writeMigration($name, $table, $create) {
        $file = pathinfo($this->creator->create($name, $this->getMigrationPath(), $table, $create, $this->input->getOption('fields')), PATHINFO_FILENAME);

        $this->line("<info>Created Migration:</info> {$file}");
    }

}
