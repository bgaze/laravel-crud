<?php

namespace Bgaze\Crud\Themes\Vue\Builders;

use Bgaze\Crud\Themes\Classic\Builders\CreateView;
use Bgaze\Crud\Core\Field;

/**
 * The Create view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class CreateComponent extends CreateView {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('assets/js/components/' . $this->crud->getPluralsKebabSlash() . "/Create.vue");
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $path = $this->buildForm('components.create', 'partials.form-group');

        $this->crud->registerComponent('Create', '/create');

        return $path;
    }

    /**
     * Get the default template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function defaultTemplate(Field $field) {
        return '<input type="text" id="FieldName" v-model="ModelCamel.FieldName"/>';
    }

    /**
     * Get the template for a boolean field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function booleanTemplate(Field $field) {
        return '<input type="checkbox" id="FieldName" v-model="ModelCamel.FieldName"/>';
    }

    /**
     * Get the template for a enum field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function enumTemplate(Field $field) {
        $choices = $field->input()->getArgument('allowed');

        if ($field->input()->getOption('nullable')) {
            array_unshift($choices, '');
        }

        $options = array_map(function ($choice) {
            return sprintf("%s<option value=\"%s\">%s</option>", str_repeat(' ', 16), $choice, $choice);
        }, $choices);

        return sprintf("<select id=\"FieldName\" v-model=\"ModelCamel.FieldName\">\n%s\n%s</select>", implode("\n", $options), str_repeat(' ', 12));
    }

    /**
     * Get the template for a text field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function textTemplate(Field $field) {
        return '<textarea id="FieldName" v-model="ModelCamel.FieldName"></textarea>';
    }

}
