<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;

/**
 * The Factory builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Factory extends Builder {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return database_path('factories/' . $this->crud->getModelFullStudly() . 'Factory.php');
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
     * Compile the content of the class.
     * 
     * @return string
     */
    protected function content() {
        $content = $this->crud->content(false);

        if ($content->isEmpty()) {
            return '// TODO';
        }

        return $content
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
     * Compile content to factory class line.
     * 
     * @return string
     */
    protected function factoryGroup(Field $field) {
        $input = $field->input();

        switch ($field->config('type')) {
            case 'boolean':
                return '(mt_rand(0, 1) === 1)';
            case 'integer':
                return 'mt_rand(0, 1000)';
            case 'float':
                return sprintf('round(mt_rand() / mt_getrandmax() * (int) str_repeat(9, %1$d - %2$d), %2$d)', $input->getArgument('total'), $input->getArgument('places'));
            case 'date':
                return "Carbon::createFromTimeStamp(\$faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp())";
            case 'array':
                $choices = $input->getArgument('allowed');
                if ($input->getOption('nullable')) {
                    array_unshift($choices, null);
                }
                return 'array_random(' . $this->compileArrayForPhp($choices) . ')';
            default:
                return "\$faker->sentence()";
        }
    }

}
