<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Entry;
use Bgaze\Crud\Core\EntriesTemplatesTrait;

/**
 * The Factory builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class FactoryFile extends Builder {

    use EntriesTemplatesTrait;

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
                ->map(function(Entry $entry) {
                    return $this->entryTemplate($entry);
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
     * Get the default template for a entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function defaultTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), '$faker->sentence()');
    }

    /**
     * Get the template for a bigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function bigIntegerTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(-2 ** 63, 2 ** 63 - 1)');
    }

    /**
     * Get the template for a boolean entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function booleanTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), '(mt_rand(0, 1) === 1)');
    }

    /**
     * Get the template for a date entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTemplate(Entry $entry) {
        return $this->timeTemplate($entry);
    }

    /**
     * Get the template for a dateTime entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTimeTemplate(Entry $entry) {
        return $this->timeTemplate($entry);
    }

    /**
     * Get the template for a dateTimeTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTimeTzTemplate(Entry $entry) {
        return $this->timeTemplate($entry);
    }

    /**
     * Get the template for a decimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function decimalTemplate(Entry $entry) {
        return $this->floatTemplate($entry);
    }

    /**
     * Get the template for a double entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function doubleTemplate(Entry $entry) {
        return $this->floatTemplate($entry);
    }

    /**
     * Get the template for a enum entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function enumTemplate(Entry $entry) {
        $input = $entry->input();
        $choices = $input->getArgument('allowed');
        if ($input->getOption('nullable')) {
            array_unshift($choices, null);
        }
        return $this->factoryGroup($entry->name(), 'array_random(' . $this->compileArrayForPhp($choices) . ')');
    }

    /**
     * Get the template for a float entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function floatTemplate(Entry $entry) {
        $input = $entry->input();
        $total = str_repeat(9, $input->getArgument('total') - $input->getArgument('places'));
        $faker = sprintf('round(mt_rand() / mt_getrandmax() * %d, %d)', $total, $input->getArgument('places'));
        return $this->factoryGroup($entry->name(), $faker);
    }

    /**
     * Get the template for a geometry entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function geometryTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a geometryCollection entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function geometryCollectionTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a integer entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function integerTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(-2147483648, 2147483647)');
    }

    /**
     * Get the template for a ipAddress entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function ipAddressTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), '$faker->ipv4');
    }

    /**
     * Get the template for a json entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function jsonTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'json_encode($faker->sentences(5))');
    }

    /**
     * Get the template for a jsonb entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function jsonbTemplate(Entry $entry) {
        return $this->jsonTemplate($entry);
    }

    /**
     * Get the template for a lineString entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function lineStringTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a longText entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function longTextTemplate(Entry $entry) {
        return $this->textTemplate($entry);
    }

    /**
     * Get the template for a macAddress entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function macAddressTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), '$faker->macAddress');
    }

    /**
     * Get the template for a mediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function mediumIntegerTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(-8388608, 8388607)');
    }

    /**
     * Get the template for a mediumText entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function mediumTextTemplate(Entry $entry) {
        return $this->textTemplate($entry);
    }

    /**
     * Get the template for a morphs entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function morphsTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a multiLineString entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function multiLineStringTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a multiPoint entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function multiPointTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a multiPolygon entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function multiPolygonTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a nullableMorphs entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function nullableMorphsTemplate(Entry $entry) {
        return $this->morphsTemplate($entry);
    }

    /**
     * Get the template for a point entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function pointTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a polygon entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function polygonTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a smallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function smallIntegerTemplate(Entry $entry) {
        return $this->factoryGroup('remember_token', 'str_random(10)');
    }

    /**
     * Get the template for a rememberToken entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function rememberTokenTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Get the template for a softDeletes entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTemplate(Entry $entry) {
        return false;
    }

    /**
     * Get the template for a softDeletesTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTzTemplate(Entry $entry) {
        return false;
    }

    /**
     * Get the template for a text entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function textTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), '$faker->text(1000)');
    }

    /**
     * Get the template for a time entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timeTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), "Carbon::createFromTimeStamp(\$faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp())");
    }

    /**
     * Get the template for a timeTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timeTzTemplate(Entry $entry) {
        return $this->timeTemplate($entry);
    }

    /**
     * Get the template for a timestamp entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampTemplate(Entry $entry) {
        return $this->timeTemplate($entry);
    }

    /**
     * Get the template for a timestampTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampTzTemplate(Entry $entry) {
        return $this->timeTemplate($entry);
    }

    /**
     * Get the template for a timestamps entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTemplate(Entry $entry) {
        return false;
    }

    /**
     * Get the template for a timestampsTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTzTemplate(Entry $entry) {
        return false;
    }

    /**
     * Get the template for a tinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function tinyIntegerTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(-128, 127)');
    }

    /**
     * Get the template for a unsignedBigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedBigIntegerTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 2 ** 64 -1)');
    }

    /**
     * Get the template for a unsignedDecimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedDecimalTemplate(Entry $entry) {
        return $this->floatTemplate($entry);
    }

    /**
     * Get the template for a unsignedInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedIntegerTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 4294967295)');
    }

    /**
     * Get the template for a unsignedMediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedMediumIntegerTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 16777215)');
    }

    /**
     * Get the template for a unsignedSmallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedSmallIntegerTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 65535)');
    }

    /**
     * Get the template for a unsignedTinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedTinyIntegerTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 255)');
    }

    /**
     * Get the template for a uuid entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function uuidTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), '$faker->uuid');
    }

    /**
     * Get the template for a year entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function yearTemplate(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(1900, 2100)');
    }

}
