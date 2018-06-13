<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * Description of Factory
 *
 * @author bgaze
 */
class Factory extends Builder {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return database_path('factories/' . $this->crud->model()->implode('') . 'Factory.php');
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('factory');
        
        $this->replace($stub, '#CONTENT', $this->content());
        
        return $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * TODO
     * 
     * @return type
     */
    protected function content() {
        return $this->crud
                        ->content(false)
                        ->map(function(Field $field) {
                            $group = $field->config('factory');

                            if (!$group) {
                                $group = $this->factoryGroup($field);
                            }

                            return "'{$field->name()}' => {$group},";
                        })
                        ->implode("\n");
    }

    /**
     * Compile content to factory class body line.
     * 
     * @return string
     */
    protected function factoryGroup(Field $field) {
        switch ($field->config('type')) {
            case 'boolean':
                return '(mt_rand(0, 1) === 1)';
            case 'integer':
                return 'mt_rand(0, 1000)';
            case 'float':
                return "(mt_rand() / mt_getrandmax()) * " . str_repeat('9', $field->input()->getArgument('total'));
            case 'date':
                return "Carbon::createFromTimeStamp(\$faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp())";
            case 'array':
                return 'array_random(' . $this->compileValueForPhp($field->input()->getArgument('allowed')) . ')';
            default:
                return "\$faker->sentence()";
        }
    }

}
