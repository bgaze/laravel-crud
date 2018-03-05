<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class SignatureCommand extends Command {

    protected $result;

    /**
     * Configure command.
     * 
     * @param string $signature
     * @param string $method
     * @param array $arguments
     */
    public function __construct() {
        $this->signature = "{field} {column} {arg1?} {arg2?} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}";
        $this->result = false;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->result = (object) [
                    'arguments' => $this->arguments(),
                    'options' => $this->options()
        ];

        var_dump($this->result);
    }

    public function runWithStringInput($str, OutputInterface $output) {
        return $this->run(new StringInput($str), $output);
    }

    /**
     * 
     * @return type
     */
    public function getResult() {
        return $this->result;
    }

}
