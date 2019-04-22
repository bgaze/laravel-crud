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
        $content = $this->crud
                ->content(false)
                ->map(function(Field $field) {
                    return $this->fieldTemplate($field);
                })
                ->filter()
                ->implode("\n");

        if (empty($content)) {
            return '// TODO';
        }

        return $content;
    }

    /**
     * Generate a factory line.
     * 
     * @param string $name      The key to populate
     * @param string $faker     The php statement to generate data
     * @return string
     */
    protected function factoryGroup($name, $faker) {
        if (empty($faker)) {
            return "// TODO: '{$name}' => '...',";
        }

        return "'{$name}' => {$faker},";
    }

    /**
     * Get the default template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function defaultTemplate(Field $field) {
        return $this->factoryGroup($field->name(), '$faker->sentence()');
    }

    /**
     * Get the template for a bigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function bigIntegerTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'mt_rand(-2 ** 63, 2 ** 63 - 1)');
    }

    /**
     * Get the template for a boolean field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function booleanTemplate(Field $field) {
        return $this->factoryGroup($field->name(), '(mt_rand(0, 1) === 1)');
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
        return $this->factoryGroup($field->name(), 'array_random(' . $this->compileArrayForPhp($choices) . ')');
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
        $faker = sprintf('round(mt_rand() / mt_getrandmax() * %d, %d)', $total, $input->getArgument('places'));
        return $this->factoryGroup($field->name(), $faker);
    }

    /**
     * Get the template for a geometry field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function geometryTemplate(Field $field) {
        return $this->factoryGroup($field->name(), null);
    }

    /**
     * Get the template for a geometryCollection field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function geometryCollectionTemplate(Field $field) {
        return $this->factoryGroup($field->name(), null);
    }

    /**
     * Get the template for a integer field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function integerTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'mt_rand(-2147483648, 2147483647)');
    }

    /**
     * Get the template for a ipAddress field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function ipAddressTemplate(Field $field) {
        return $this->factoryGroup($field->name(), '$faker->ipv4');
    }

    /**
     * Get the template for a json field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function jsonTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'json_encode($faker->sentences(5))');
    }

    /**
     * Get the template for a jsonb field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function jsonbTemplate(Field $field) {
        return $this->jsonTemplate($field);
    }

    /**
     * Get the template for a lineString field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function lineStringTemplate(Field $field) {
        return $this->factoryGroup($field->name(), null);
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
        return $this->factoryGroup($field->name(), '$faker->macAddress');
    }

    /**
     * Get the template for a mediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function mediumIntegerTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'mt_rand(-8388608, 8388607)');
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
        return $this->factoryGroup($field->name(), null);
    }

    /**
     * Get the template for a multiLineString field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function multiLineStringTemplate(Field $field) {
        return $this->factoryGroup($field->name(), null);
    }

    /**
     * Get the template for a multiPoint field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function multiPointTemplate(Field $field) {
        return $this->factoryGroup($field->name(), null);
    }

    /**
     * Get the template for a multiPolygon field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function multiPolygonTemplate(Field $field) {
        return $this->factoryGroup($field->name(), null);
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
        return $this->factoryGroup($field->name(), null);
    }

    /**
     * Get the template for a polygon field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function polygonTemplate(Field $field) {
        return $this->factoryGroup($field->name(), null);
    }

    /**
     * Get the template for a smallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function smallIntegerTemplate(Field $field) {
        return $this->factoryGroup('remember_token', 'str_random(10)');
    }

    /**
     * Get the template for a rememberToken field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function rememberTokenTemplate(Field $field) {
        return $this->factoryGroup($field->name(), null);
    }

    /**
     * Get the template for a softDeletes field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function softDeletesTemplate(Field $field) {
        return false;
    }

    /**
     * Get the template for a softDeletesTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function softDeletesTzTemplate(Field $field) {
        return false;
    }

    /**
     * Get the template for a text field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function textTemplate(Field $field) {
        return $this->factoryGroup($field->name(), '$faker->text(1000)');
    }

    /**
     * Get the template for a time field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timeTemplate(Field $field) {
        return $this->factoryGroup($field->name(), "Carbon::createFromTimeStamp(\$faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp())");
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
     * Get the template for a timestamps field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampsTemplate(Field $field) {
        return false;
    }

    /**
     * Get the template for a timestampsTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampsTzTemplate(Field $field) {
        return false;
    }

    /**
     * Get the template for a tinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function tinyIntegerTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'mt_rand(-128, 127)');
    }

    /**
     * Get the template for a unsignedBigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedBigIntegerTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'mt_rand(0, 2 ** 64 -1)');
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
        return $this->factoryGroup($field->name(), 'mt_rand(0, 4294967295)');
    }

    /**
     * Get the template for a unsignedMediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedMediumIntegerTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'mt_rand(0, 16777215)');
    }

    /**
     * Get the template for a unsignedSmallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedSmallIntegerTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'mt_rand(0, 65535)');
    }

    /**
     * Get the template for a unsignedTinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedTinyIntegerTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'mt_rand(0, 255)');
    }

    /**
     * Get the template for a uuid field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function uuidTemplate(Field $field) {
        return $this->factoryGroup($field->name(), '$faker->uuid');
    }

    /**
     * Get the template for a year field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function yearTemplate(Field $field) {
        return $this->factoryGroup($field->name(), 'mt_rand(1900, 2100)');
    }

}
