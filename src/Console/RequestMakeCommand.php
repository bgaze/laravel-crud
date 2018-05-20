<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Bgaze\Crud\Support\ConsoleHelpersTrait;

class RequestMakeCommand extends Command {

    use ConsoleHelpersTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:request 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--t|theme= : The theme to use to generate CRUD.}{--r|rules=* : The lines to insert into request rules array.}
        {--r|rules=* : The lines to insert into request rules array.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD form request class';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        // Get CRUD theme.
        $theme = $this->getTheme();

        // Write request file.
        $path = $theme->generatePhpFile('request', $theme->requestPath(), function($theme, $stub) {
            $rules = $this->option('rules');

            $theme->replace($stub, '#RULES', empty($rules) ? '//' : implode(",\n", $rules));

            return $stub;
        });

        // Show success message.
        $this->info("Request created : <fg=white>$path</>");
    }

}
