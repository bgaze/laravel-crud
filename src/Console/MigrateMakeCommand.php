<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Bgaze\Crud\Support\CrudHelpersTrait;

class MigrateMakeCommand extends Command {

    use CrudHelpersTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:migration 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--c|content=* : The PHP lines of your migration body (one line by row).}
        {--theme= : The theme to use to generate CRUD.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD migration file';

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(Composer $composer) {
        parent::__construct();
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        // Get CRUD theme.
        $theme = $this->getTheme();

        // Write migration file.
        $path = $theme->generatePhpFile('migration', $theme->getMigrationPath(), function($theme, $stub) {
            $theme
                    ->replace($stub, 'TableName')
                    ->replace($stub, 'MigrationClass')
                    ->replace($stub, '#CONTENT', implode("\n", $this->option('content')))
            ;

            return $stub;
        });

        // Update autoload.
        $this->composer->dumpAutoloads();

        // Show success message.
        $this->info("Migration created : <fg=white>$path</>");
    }

}
