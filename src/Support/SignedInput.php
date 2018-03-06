<?php

namespace Bgaze\Crud\Support;

use Illuminate\Console\Parser;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * Allow to use Command signature mechanism to parse & validate user input.
 * 
 * @author bgaze
 */
class SignedInput {

    /**
     * 
     * @param string $signature
     * @return InputDefinition
     */
    public static function definition($signature) {
        // Parse signature.
        list($name, $arguments, $options) = Parser::parse($signature);

        // Prepare input definition.
        $definition = new InputDefinition();
        foreach ($arguments as $argument) {
            $definition->addArgument($argument);
        }
        foreach ($options as $option) {
            $definition->addOption($option);
        }

        // Return definition.
        return $definition;
    }

    /**
     * 
     * @param string $signature
     * @param boolean $short
     * @return string
     */
    public static function help($signature, $short = false) {
        return self::definition($signature)->getSynopsis($short);
    }

    /**
     * 
     * @param string $signature
     * @param string $value
     * @return StringInput
     */
    public static function input($signature, $value) {
        // Get signature definition.
        $definition = self::definition($signature);

        // Bind value with definition & validate.
        $input = new StringInput($value);
        $input->bind($definition);
        $input->validate();

        // Return results.
        return $input;
    }

}
