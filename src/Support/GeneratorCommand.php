<?php

namespace Bgaze\Crud\Support;

use Illuminate\Console\Command;
use Bgaze\Crud\Theme\Crud;

/**
 * Description of GeneratorCommand
 *
 * @author bgaze
 */
abstract class GeneratorCommand extends Command {

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        // Instantiate CRUD.
        $crud = $this->laravel->make($this->option('theme') ? $this->option('theme') : config('crud.theme'), [
            'model' => $this->argument('model'),
            'plural' => $this->option('plural')
        ]);

        // Get timestamps value.
        if ($this->hasOption('timestamps') && $this->option('timestamps')) {
            $crud->content->timestamps = $this->option('timestamps');
        }

        // Get softDeletes value.
        if ($this->hasOption('soft-delete') && $this->option('soft-delete')) {
            $crud->content->softDeletes = $this->option('soft-delete');
        }

        // Add content.
        if ($this->hasOption('content')) {
            foreach ($this->option('content') as $question) {
                list($field, $data) = $this->parseUserInput($question);
                $crud->content->add($field, $data);
            }
        }

        // Build.
        $this->build($crud);
    }

    /**
     * TODO
     * 
     * @param Crud $crud
     */
    abstract protected function build(Crud $crud);

    /**
     * TODO
     * 
     * @param string $question
     * @return array
     * @throws \Exception
     */
    protected function parseUserInput($question) {
        $reg = '/^(' . collect(config('crud-definitions.fields'))->keys()->implode('|') . ')(\s.*)?$/';

        if (!preg_match($reg, $question, $matches)) {
            throw new \Exception("Invalid input '$question'.");
        }

        return [
            $matches[1], // Field name
            isset($matches[2]) ? trim($matches[2]) : '' // User input
        ];
    }

}
