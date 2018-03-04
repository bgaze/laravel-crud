<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;

class SignatureCommand extends Command {

    protected $method;
    protected $arguments;
    protected $result;

    /**
     * Configure command.
     * 
     * @param string $signature
     * @param string $method
     * @param array $arguments
     */
    public function __construct($signature, $method, array $arguments) {
        $this->signature = $signature;

        parent::__construct();

        $this->method = $method;
        $this->arguments = $arguments;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->result = call_user_func_array([$this, $this->method], $this->arguments);
    }

    /**
     * 
     * @return type
     */
    function getResult() {
        return $this->result;
    }

}
