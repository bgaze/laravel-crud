<?php

namespace Bgaze\Crud\Themes\Vue;

use Bgaze\Crud\Core\Field;
use Bgaze\Crud\Themes\Classic\FormBuilder as Base;

/**
 * A form view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
abstract class FormBuilder extends Base {

    /**
     * Compile a boolean field to checkbox.
     * 
     * @param \Bgaze\Crud\Core\Field $field     The field to compile
     * @return string
     */
    protected function booleanTemplate(Field $field) {
        return '<input type="checkbox" id="FieldName" v-model="ModelCamel.FieldName"/>';
    }

    /**
     * Compile an array field to select.
     * 
     * @param \Bgaze\Crud\Core\Field $field     The field to compile
     * @return string
     */
    protected function arrayTemplate(Field $field) {
        $choices = $field->input()->getArgument('allowed');

        if ($field->input()->getOption('nullable')) {
            array_unshift($choices, '');
        }

        $options = array_map(function ($choice) {
            return sprintf("%s<option value=\"%s\">%s</option>", str_repeat(' ', 12), $choice, $choice);
        }, $choices);

        return sprintf("<select id=\"FieldName\" v-model=\"ModelCamel.FieldName\">\n%s\n%s</select>", implode("\n", $options), str_repeat(' ', 8));
    }

    /**
     * Compile a field to text input.
     * 
     * @param \Bgaze\Crud\Core\Field $field     The field to compile
     * @return string
     */
    protected function defaultTemplate(Field $field) {
        return '<input type="text" id="FieldName" v-model="ModelCamel.FieldName"/>';
    }

}
