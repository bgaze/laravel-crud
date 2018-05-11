<?php

namespace Bgaze\Crud\Support;

/**
 * TODO
 */
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
     * Prepare value for PHP generation depending on it's type
     * 
     * @param mixed $value
     * @return mixed
     */
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
