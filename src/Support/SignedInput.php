<?php

namespace Bgaze\Crud\Support;

use Illuminate\Console\Parser;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;

/**
 * This class allows to use Commands signature syntax to manage user input.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class SignedInput {

    /**
     * The signature of the input.
     * 
     * @var string 
     */
    protected $signature;

    /**
     * The command part of the signature.
     * 
     * @var string 
     */
    protected $command;

    /**
     * The InputDefinition instance.
     *
     * @var \Symfony\Component\Console\Input\InputDefinition
     */
    protected $definition;

    /**
     * The original input of the user.
     * 
     * @var string 
     */
    protected $question;

    /**
     * The StringInput instance.
     *
     * @var \Symfony\Component\Console\Input\StringInput
     */
    protected $input;

    /**
     * The class constructor.
     * 
     * @param string $signature The signature of the input
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
     * Instanciate StringInput and bind to user input.
     * 
     * @param string $question The user signed input string
     */
    public function ask($question) {
        $this->input = new StringInput($question);
        $this->input->bind($this->definition);
    }

    /**
     * Validate user question.
     * 
     * @param array $rules An optionnal set of validation rules .
     * @throws \Exception
     */
    public function validate(array $rules = []) {
        // Check that input matches signature format.
        $this->input->validate();

        // Use custom validation rules.
        if (!empty($rules)) {
            $input = array_merge($this->input->getOptions(), $this->input->getArguments());
            $validator = \Validator::make($input, $rules);
            if ($validator->fails()) {
                throw new \Exception(implode("\n", $validator->errors()->all()));
            }
        }
    }

    /**
     * The SignedInput signature.
     * 
     * @return string
     */
    public function signature() {
        return $this->signature;
    }

    /**
     * The command part of the signature.
     * 
     * @return string
     */
    public function command() {
        return $this->command;
    }

    /**
     * The InputDefinition instance.
     * 
     * @return \Symfony\Component\Console\Input\InputDefinition
     */
    public function definition() {
        return $this->definition;
    }

    /**
     * The original input of the user.
     * 
     * @return string
     */
    public function question() {
        return $this->question;
    }

    /**
     * The StringInput instance.
     * 
     * @return \Symfony\Component\Console\Input\StringInput
     * @throws \Exception
     */
    public function input() {
        if (!($this->input instanceof StringInput)) {
            throw new \Exception('No question has been provided, please use the "ask" method.');
        }

        return $this->input;
    }

}
