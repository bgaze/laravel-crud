<?php

namespace Bgaze\Crud\Support;

trait CrudHelpersTrait {

    /**
     * Validate a camel case string.
     * 
     * @param string $str
     * @return boolean
     */
    protected function isValidCamelCase($str) {
        if (!preg_match('/^([A-Z][a-z0-9]+)+$/', $str)) {
            $this->error("This is not a valid camel cased string.");
            return false;
        }

        return true;
    }

    /**
     * Validate a snake case string.
     * 
     * @param string $str
     * @return boolean
     */
    protected function isValidSnakeCase($str) {
        if (!preg_match('/^[a-z][a-z0-9]*(_[a-z0-9]+)*$/', $str)) {
            $this->error("This is not a valid kebab cased string.");
            return false;
        }

        return true;
    }

    /**
     * Prepare fields définition.
     * 
     * @return \Illuminate\Support\Collection
     */
    protected function fieldsDefinition() {
        return collect(config('crud-definitions.migrate.fields'))->map(function($definition, $name) {
                    $tmp = (object) $definition;

                    $tmp->validation = isset($tmp->validation) ? array_merge(config('crud-definitions.migrate.validation'), $tmp->validation) : config('crud-definitions.migrate.validation');

                    $help = SignedInput::help($tmp->signature);
                    $tmp->help = $name . ' ' . $help;

                    list($options, $arguments) = explode(' [--] ', $help);
                    $tmp->help_row = [
                        'name' => $name,
                        'arguments' => $arguments,
                        'options' => trim(str_replace('] [', ' ', $options), '[]')
                    ];

                    return $tmp;
                });
    }

    protected function compileValueForPhp($value) {
        if ($value === true || $value === 'true') {
            return 'true';
        }

        if ($value === false || $value === 'false') {
            return 'false';
        }

        if ($value === null || $value === 'null') {
            return 'null';
        }

        if (!is_numeric($value)) {
            return "'" . addslashes($value) . "'";
        }

        return $value;
    }

}
