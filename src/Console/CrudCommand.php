<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Bgaze\Crud\Console\SignatureCommand;
use Illuminate\Console\Parser;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Input\InputDefinition;

class CrudCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bgaze:crud:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $fields = config('crud_dic.migrate.fields');
        $q = false;

        while ($q === false) {
            $q = $this->anticipate('Add a field (empty = quit)', array_keys($fields));

            if (empty($q)) {
                break;
            }

            $name = explode(' ', $q)[0];
            if (!isset($fields[$name])) {
                $this->error("Undefined field '$name'.");
                $q = false;
                continue;
            }

            $signature = $fields[$name]['signature'];
            list($name, $arguments, $options) = Parser::parse($signature);

            $definition = new InputDefinition();
            foreach ($arguments as $argument) {
                $this->getDefinition()->addArgument($argument);
            }
            foreach ($options as $option) {
                $this->getDefinition()->addOption($option);
            }

            $input = new StringInput($q);
            $input->bind($definition);


            var_dump($name);
            var_dump($input->getArguments());
            var_dump($input->getOptions());
            die();
        }
    }

}
