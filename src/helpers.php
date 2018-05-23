<?php

/**
 * Prepare value for PHP generation depending on it's type
 * 
 * @param mixed $value
 * @return mixed
 */
function compile_value_for_php($value) {
    if (is_array($value)) {
        return collect($value)->map(function($v) {
                    return compile_value_for_php($v);
                })->implode(', ');
    }

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
