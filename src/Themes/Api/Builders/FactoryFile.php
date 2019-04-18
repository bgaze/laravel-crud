<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;
use Bgaze\Crud\Core\FieldsTemplatesTrait;

/**
 * The Factory builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class FactoryFile extends Builder {

    use FieldsTemplatesTrait;

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
     */
    public function build() {
        $stub = $this->stub('factory');

        $this->replace($stub, '#CONTENT', $this->content());

        $this->generatePhpFile($this->file(), $stub);
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
                            $faker = $this->fieldTemplate($field);

                            if (empty($faker)) {
                                return "// TODO : '{$field->name()}' => '...',";
                            }

                            return "'{$field->name()}' => {$faker},";
                        })
                        ->filter()
                        ->implode("\n");
    }

    /**
     * Get the default template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function defaultTemplate(Field $field) {
        return "\$faker->sentence()";
    }

    /**
     * Get the template for a bigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function bigIntegerTemplate(Field $field) {
        return 'mt_rand(-2 ** 63, 2 ** 63 - 1)';
    }

    /**
     * Get the template for a boolean field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function booleanTemplate(Field $field) {
        return '(mt_rand(0, 1) === 1)';
    }

    /**
     * Get the template for a date field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function dateTemplate(Field $field) {
        return $this->timeTemplate($field);
    }

    /**
     * Get the template for a dateTime field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function dateTimeTemplate(Field $field) {
        return $this->timeTemplate($field);
    }

    /**
     * Get the template for a dateTimeTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function dateTimeTzTemplate(Field $field) {
        return $this->timeTemplate($field);
    }

    /**
     * Get the template for a decimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function decimalTemplate(Field $field) {
        return $this->floatTemplate($field);
    }

    /**
     * Get the template for a double field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function doubleTemplate(Field $field) {
        return $this->floatTemplate($field);
    }

    /**
     * Get the template for a enum field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function enumTemplate(Field $field) {
        $input = $field->input();
        $choices = $input->getArgument('allowed');
        if ($input->getOption('nullable')) {
            array_unshift($choices, null);
        }
        return 'array_random(' . $this->compileArrayForPhp($choices) . ')';
    }

    /**
     * Get the template for a float field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function floatTemplate(Field $field) {
        $input = $field->input();
        $total = str_repeat(9, $input->getArgument('total') - $input->getArgument('places'));
        return sprintf('round(mt_rand() / mt_getrandmax() * %d, %d)', $total, $input->getArgument('places'));
    }

    /**
     * Get the template for a geometry field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function geometryTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a geometryCollection field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function geometryCollectionTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a integer field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function integerTemplate(Field $field) {
        return 'mt_rand(-2147483648, 2147483647)';
    }

    /**
     * Get the template for a ipAddress field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function ipAddressTemplate(Field $field) {
        return "\$faker->ipv4";
    }

    /**
     * Get the template for a json field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function jsonTemplate(Field $field) {
        return "\$faker->sentences(5)";
    }

    /**
     * Get the template for a jsonb field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function jsonbTemplate(Field $field) {
        return "\$faker->sentences(5)";
    }

    /**
     * Get the template for a lineString field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function lineStringTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a longText field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function longTextTemplate(Field $field) {
        return $this->textTemplate($field);
    }

    /**
     * Get the template for a macAddress field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function macAddressTemplate(Field $field) {
        return "\$faker->macAddress";
    }

    /**
     * Get the template for a mediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function mediumIntegerTemplate(Field $field) {
        return 'mt_rand(-8388608, 8388607)';
    }

    /**
     * Get the template for a mediumText field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function mediumTextTemplate(Field $field) {
        return $this->textTemplate($field);
    }

    /**
     * Get the template for a morphs field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function morphsTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a multiLineString field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function multiLineStringTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a multiPoint field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function multiPointTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a multiPolygon field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function multiPolygonTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a nullableMorphs field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function nullableMorphsTemplate(Field $field) {
        return $this->morphsTemplate($field);
    }

    /**
     * Get the template for a point field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function pointTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a polygon field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function polygonTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a smallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function smallIntegerTemplate(Field $field) {
        return 'mt_rand(-32768, 32767)';
    }

    /**
     * Get the template for a text field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function textTemplate(Field $field) {
        return "\$faker->text(1000)";
    }

    /**
     * Get the template for a time field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timeTemplate(Field $field) {
        return "Carbon::createFromTimeStamp(\$faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp())";
    }

    /**
     * Get the template for a timeTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timeTzTemplate(Field $field) {
        return $this->timeTemplate($field);
    }

    /**
     * Get the template for a timestamp field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampTemplate(Field $field) {
        return $this->timeTemplate($field);
    }

    /**
     * Get the template for a timestampTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampTzTemplate(Field $field) {
        return $this->timeTemplate($field);
    }

    /**
     * Get the template for a tinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function tinyIntegerTemplate(Field $field) {
        return 'mt_rand(-128, 127)';
    }

    /**
     * Get the template for a unsignedBigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedBigIntegerTemplate(Field $field) {
        return 'mt_rand(0, 2 ** 64 -1)';
    }

    /**
     * Get the template for a unsignedDecimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedDecimalTemplate(Field $field) {
        return $this->floatTemplate($field);
    }

    /**
     * Get the template for a unsignedInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedIntegerTemplate(Field $field) {
        return 'mt_rand(0, 4294967295)';
    }

    /**
     * Get the template for a unsignedMediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedMediumIntegerTemplate(Field $field) {
        return 'mt_rand(0, 16777215)';
    }

    /**
     * Get the template for a unsignedSmallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedSmallIntegerTemplate(Field $field) {
        return 'mt_rand(0, 65535)';
    }

    /**
     * Get the template for a unsignedTinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedTinyIntegerTemplate(Field $field) {
        return 'mt_rand(0, 255)';
    }

    /**
     * Get the template for a uuid field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function uuidTemplate(Field $field) {
        return "\$faker->uuid";
    }

    /**
     * Get the template for a year field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function yearTemplate(Field $field) {
        return 'mt_rand(1900, 2100)';
    }

}
