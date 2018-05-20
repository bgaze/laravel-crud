<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Bgaze\Crud\Support\ConsoleHelpersTrait;

class FactoryMakeCommand extends Command {

    use ConsoleHelpersTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:factory 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--t|theme= : The theme to use to generate CRUD.}
        {--c|content=* : The lines to insert into factory array.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD model factory file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        // Get CRUD theme.
        $theme = $this->getTheme();

        // Write request file.
        $path = $theme->generatePhpFile('factory', $theme->factoryPath(), function($theme, $stub) {
            $content = $this->option('content');

            $theme->replace($stub, '#CONTENT', empty($content) ? '//' : implode(",\n", $content));

            return $stub;
        });

        // Show success message.
        $this->info("Factory file created : <fg=white>$path</>");
    }

}
