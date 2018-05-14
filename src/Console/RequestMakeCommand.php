<?php

namespace Bgaze\Crud\Console;

use Illuminate\Foundation\Console\RequestMakeCommand as Base;

class RequestMakeCommand extends Base {

    use \Bgaze\Crud\Support\CrudHelpersTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:request 
        {name : The name of the class.}
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
     * @return bool|null
     */
    public function handle() {
        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type . ' already exists!');
            return false;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($name));
        $this->finalizeFileGeneration($path, 'Request created : %s');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name) {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
                        ->replaceRules($stub)
                        ->replaceClass($stub, $name);
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getRulesInput() {
        return collect($this->option('rules'))->map(function($v) {
                    return trim($v);
                })->filter();
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @return $this
     */
    protected function replaceRules(&$stub) {
        $stub = str_replace('#RULES', $this->getRulesInput()->implode(",\n"), $stub);

        return $this;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() {
        return config('crud.stubs.request');
    }

}
