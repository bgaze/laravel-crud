<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Bgaze\Crud\Support;

use Symfony\Component\Console\Input\StringInput;
use Bgaze\Crud\Support\SignedInput;
use Validator;

/**
 * Description of MigrateField
 *
 * @author bgaze
 */
class MigrateField {

    protected $name;
    protected $signature;
    protected $description;
    protected $template;
    protected $rules;
    protected $messages;

    /**
     * 
     * @param string $name
     * @param string $signature
     * @param string $description
     */
    function __construct($name, $signature, $description, $template) {
        $this->name = $name;
        $this->signature = $signature;
        $this->description = $description;
        $this->template = $template;
        $this->rules = [];
        $this->messages = [];
    }

    /**
     * 
     * @param string $value
     * @return string
     * @throws \Exception
     * @return StringInput
     */
    public function input($value) {
        $input = SignedInput::input($this->signature, $value);

        if (!empty($this->rules)) {
            $validator = Validator::make($input->getOptions() + $input->getArguments(), $this->rules, $this->messages);

            if ($validator->fails()) {
                throw new \Exception(implode("\n", $validator->errors()->all()));
            }
        }

        return $input;
    }

    public function compile(StringInput $input) {
        $template = $this->template;

        foreach ($input->getArguments() as $k => $v) {
            $template = str_replace("%$k", $v === null ? 'null' : $v, $template);
        }

        foreach ($input->getOptions() as $k => $v) {
            if ($v) {
                $template .= str_replace('%value', $v, config("crud_dic.migrate.modifiers.$k"));
            }
        }

        return $template . ';';
    }

    /**
     * 
     * @param boolean $parsed
     * @return mixed
     */
    public function help($parsed = false) {
        if (!$parsed) {
            return $this->name . ' ' . SignedInput::help($this->signature);
        }


        $definition = SignedInput::definition($this->signature);

        list($options, $arguments) = explode(' [--] ', SignedInput::help($this->signature));

        return [
            'name' => $this->name,
            'arguments' => $arguments,
            'options' => trim(str_replace('] [', ' ', $options), '[]')
        ];
    }

    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @return string
     */
    public function getSignature() {
        return $this->signature;
    }

    /**
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * 
     * @return array
     */
    public function getRules() {
        return $this->rules;
    }

    /**
     * 
     * @return array
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * 
     * @param string $name
     * @return MigrateField
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * 
     * @param string $signature
     * @return MigrateField
     */
    public function setSignature($signature) {
        $this->signature = $signature;
        return $this;
    }

    /**
     * 
     * @param string $description
     * @return MigrateField
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * 
     * @param array $rules
     * @param array $defaults
     * @return MigrateField
     */
    public function setRules(array $rules, array $defaults = []) {
        $this->rules = !empty($defaults) ? array_merge($defaults, $rules) : $rules;
        return $this;
    }

    /**
     * 
     * @param array $messages
     * @return MigrateField
     */
    public function setMessages(array $messages) {
        $this->messages = $messages;
        return $this;
    }

}
