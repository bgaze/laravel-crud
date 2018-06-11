<?php

namespace Bgaze\Crud\Support;

use Illuminate\Console\Parser;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;

/**
 * Description of SignedInput
 *
 * @author bgaze
 */
class SignedInput {

    /**
     * TODO
     * 
     * @var type 
     */
    protected $signature;

    /**
     * TODO
     * 
     * @var type 
     */
    protected $command;

    /**
     * The StringInput instance.
     *
     * @var \Symfony\Component\Console\Input\StringInput
     */
    protected $definition;

    /**
     * TODO
     * 
     * @var type 
     */
    protected $question;

    /**
     * The StringInput instance.
     *
     * @var \Symfony\Component\Console\Input\StringInput
     */
    protected $input;

    /**
     * TODO
     * 
     * @param type $signature
     */
    public function __construct($signature) {
        // Store signature.
        $this->signature = $signature;

        // Parse signature.
        list($this->command, $arguments, $options) = Parser::parse($signature);

        // Build InputDefinition.
        $this->definition = new InputDefinition();
        foreach ($arguments as $argument) {
            $this->definition->addArgument($argument);
        }
        foreach ($options as $option) {
            $this->definition->addOption($option);
        }
    }

    /**
     * TODO
     * 
     * @param type $question
     */
    public function ask($question) {
        $this->input = new StringInput($question);
        $this->input->bind($this->definition);
    }

    /**
     * TODO
     * 
     * @param array $rules
     * @throws \Exception
     */
    public function validate(array $rules = []) {
        // Check that input matches signature format.
        $this->input->validate();

        // Use custom validation rules.
        if (!empty($rules)) {
            $input = $this->input->getOptions() + $this->input->getArguments();
            $validator = \Validator::make($input, $rules);
            if ($validator->fails()) {
                throw new \Exception(implode("\n", $validator->errors()->all()));
            }
        }
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function signature() {
        return $this->signature;
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function command() {
        return $this->command;
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function definition() {
        return $this->definition;
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function question() {
        return $this->question;
    }

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function input() {
        if (!($this->input instanceof StringInput)) {
            throw new \Exception('No question has been provided, please use the "ask" method.');
        }

        return $this->input;
    }

}
