<?php

namespace Bgaze\Crud\Support\Theme;

use Bgaze\Crud\Theme\Crud;
use Bgaze\Crud\Support\CrudHelpersTrait;

/**
 * Description of Field
 *
 * @author bgaze
 */
class Field {

    use CrudHelpersTrait;

    public $type;
    public $template;
    public $name;
    public $options;
    public $arguments;

    public function __construct($type, $template, $name, array $arguments, array $options = []) {
        $this->type = $type;
        $this->template = $template;
        $this->name = $name;
        $this->options = $options;
        $this->arguments = $arguments;
    }

    /**
     * Compile migration field to PHP sentence.
     * 
     * @return string
     */
    public function compileMigrationRow() {
        $tmp = $this->template;

        foreach ($this->arguments as $k => $v) {
            $tmp = str_replace("%$k", $this->compileValueForPhp($v), $tmp);
        }

        foreach ($this->options as $k => $v) {
            if ($v) {
                $tmp .= str_replace('%value', Crud::compileValueForPhp($v), config("crud-definitions.migrate.modifiers.$k"));
            }
        }

        return $tmp . ';';
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function compileRequestRow() {
        
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function compileTableHead() {
        
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function compileTableBody() {
        
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function compileFormRow() {
        
    }

}
