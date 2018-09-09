<?php

namespace Bgaze\Crud\Themes\Vue\Builders;

use Bgaze\Crud\Themes\Classic\Builders\Views\Create;

/**
 * The Create view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class CreateComponent extends Create {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('assets/js/components/' . $this->crud->getPluralsKebabSlash() . "/create.blade.php");
    }

    /**
     * Build a form field
     * 
     * @param \Bgaze\Crud\Core\Field $field
     * @return string
     */
    protected function formField(Field $field) {
        switch ($field->config('type')) {
            case 'boolean':
                return '<input type="checkbox" id="FieldName" v-model="ModelCamel.FieldName"/>';
            case 'array':
                $choices = $field->input()->getArgument('allowed');

                if ($field->input()->getOption('nullable')) {
                    array_unshift($choices, '');
                }

                $field = '<select id="FieldName" v-model="ModelCamel.FieldName">';
                foreach ($choices as $choice) {
                    $field .= sprintf("\n%s<option value=\"%s\">%s</option>", str_repeat(' ', 12), $choice, $choice);
                }
                return $field . str_repeat(' ', 8) . '</select>';
            default:
                return '<input type="text" id="FieldName" v-model="ModelCamel.FieldName"/>';
        }
    }

}
