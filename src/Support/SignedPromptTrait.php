<?php

namespace Bgaze\Crud\Support;

use Bgaze\Crud\Console\SignatureCommand;

/**
 * Allow to use Command signature mechanism to parse & validate user input.
 */
trait SignedPromptTrait {

    /**
     * Create a new SignedPromptCommand instance.
     *
     * @param  string  $signature
     * @return SignedPromptCommand
     */
    public function signature($signature) {
        return new SignatureCommand($signature);
    }

    /**
     * Prompt the user for input.
     *
     * @param  string  $signature
     * @param  string  $question
     * @param  string  $default
     * @return string
     */
    public function ask($signature, $question, $default = null) {
        $command = new SignatureCommand($signature, 'ask', [$question, $default]);
        $command->run($this->input, $this->output);
        return $command->getResult();
    }

    /**
     * Prompt the user for input with auto completion.
     *
     * @param  string  $signature
     * @param  string  $question
     * @param  array   $choices
     * @param  string  $default
     * @return string
     */
    public function signedAnticipate($question, array $choices, $default = null) {
        $command = new SignatureCommand($signature, 'ask', [$question, $default]);
        $command->run($this->input, $this->output);
        return $command->getResult();
    }

    /**
     * Give the user a single choice from an array of answers.
     *
     * @param  string  $signature
     * @param  string  $question
     * @param  array   $choices
     * @param  string  $default
     * @param  mixed   $attempts
     * @param  bool    $multiple
     * @return string
     */
    public function signedChoice($question, array $choices, $default = null, $attempts = null, $multiple = null) {
        $command = new SignatureCommand($signature, 'ask', [$question, $default]);
        $command->run($this->input, $this->output);
        return $command->getResult();
    }

}
