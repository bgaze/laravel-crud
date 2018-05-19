<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Bgaze\Crud\Support\CrudHelpersTrait;

class RequestMakeCommand extends Command {

    use CrudHelpersTrait;

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

        // Write migration file.
        $path = $theme->generatePhpFile('request', $theme->getRequestPath(), function($theme, $stub) {
            $theme
                    ->replace($stub, 'RequestNamespace')
                    ->replace($stub, 'RequestClass')
                    ->replace($stub, '/*RULES*/', $this->getRules())
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Request created : <fg=white>$path</>");
    }

    /**
     * TODO
     * 
     * @return type
     */
    protected function getRules() {
        $rules = collect($this->option('rules'))->map(function($v) {
                    return trim($v);
                })->filter();

        if ($rules->isEmpty()) {
            return '';
        }

        return $rules->implode(",\n");
    }

}
