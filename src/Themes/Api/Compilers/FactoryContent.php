<?php

namespace Bgaze\Crud\Themes\Api\Compilers;

use Bgaze\Crud\Core\Compiler;
use Bgaze\Crud\Core\Entry;

/**
 * Description of FactoryFaker
 *
 * @author bgaze
 */
class FactoryContent extends Compiler {

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
     * Get the default compilation function for an entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The compiled entry
     */
    public function compileDefault(Entry $entry) {
        if ($entry->isIndex()) {
            return null;
        }

        return $this->factoryGroup($entry->name(), '$faker->sentence()');
    }

    /**
     * Compile a bigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function bigInteger(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(-2 ** 63, 2 ** 63 - 1)');
    }

    /**
     * Compile a boolean entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function boolean(Entry $entry) {
        return $this->factoryGroup($entry->name(), '(mt_rand(0, 1) === 1)');
    }

    /**
     * Compile a date entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function date(Entry $entry) {
        return $this->time($entry);
    }

    /**
     * Compile a dateTime entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTime(Entry $entry) {
        return $this->time($entry);
    }

    /**
     * Compile a dateTimeTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTimeTz(Entry $entry) {
        return $this->time($entry);
    }

    /**
     * Compile a decimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function decimal(Entry $entry) {
        return $this->float($entry);
    }

    /**
     * Compile a double entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function double(Entry $entry) {
        return $this->float($entry);
    }

    /**
     * Compile a enum entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function enum(Entry $entry) {
        $input = $entry->input();
        $choices = $input->getArgument('allowed');
        if ($input->getOption('nullable')) {
            array_unshift($choices, null);
        }
        return $this->factoryGroup($entry->name(), 'array_random(' . $this->compileArrayForPhp($choices) . ')');
    }

    /**
     * Compile a float entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function float(Entry $entry) {
        $input = $entry->input();
        $total = str_repeat(9, $input->getArgument('total') - $input->getArgument('places'));
        $faker = sprintf('round(mt_rand() / mt_getrandmax() * %d, %d)', $total, $input->getArgument('places'));
        return $this->factoryGroup($entry->name(), $faker);
    }

    /**
     * Compile a geometry entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function geometry(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a geometryCollection entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function geometryCollection(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a integer entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function integer(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(-2147483648, 2147483647)');
    }

    /**
     * Compile a ipAddress entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function ipAddress(Entry $entry) {
        return $this->factoryGroup($entry->name(), '$faker->ipv4');
    }

    /**
     * Compile a json entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function json(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'json_encode($faker->sentences(5))');
    }

    /**
     * Compile a jsonb entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function jsonb(Entry $entry) {
        return $this->json($entry);
    }

    /**
     * Compile a lineString entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function lineString(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a longText entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function longText(Entry $entry) {
        return $this->text($entry);
    }

    /**
     * Compile a macAddress entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function macAddress(Entry $entry) {
        return $this->factoryGroup($entry->name(), '$faker->macAddress');
    }

    /**
     * Compile a mediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function mediumInteger(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(-8388608, 8388607)');
    }

    /**
     * Compile a mediumText entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function mediumText(Entry $entry) {
        return $this->text($entry);
    }

    /**
     * Compile a morphs entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function morphs(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a multiLineString entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function multiLineString(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a multiPoint entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function multiPoint(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a multiPolygon entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function multiPolygon(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a nullableMorphs entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function nullableMorphs(Entry $entry) {
        return $this->morphs($entry);
    }

    /**
     * Compile a point entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function point(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a polygon entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function polygon(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a smallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function smallInteger(Entry $entry) {
        return $this->factoryGroup('remember_token', 'str_random(10)');
    }

    /**
     * Compile a rememberToken entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function rememberToken(Entry $entry) {
        return $this->factoryGroup($entry->name(), null);
    }

    /**
     * Compile a softDeletes entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletes(Entry $entry) {
        return false;
    }

    /**
     * Compile a softDeletesTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTz(Entry $entry) {
        return false;
    }

    /**
     * Compile a text entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function text(Entry $entry) {
        return $this->factoryGroup($entry->name(), '$faker->text(1000)');
    }

    /**
     * Compile a time entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function time(Entry $entry) {
        return $this->factoryGroup($entry->name(), "Carbon::createFromTimeStamp(\$faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp())");
    }

    /**
     * Compile a timeTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timeTz(Entry $entry) {
        return $this->time($entry);
    }

    /**
     * Compile a timestamp entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestamp(Entry $entry) {
        return $this->time($entry);
    }

    /**
     * Compile a timestampTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampTz(Entry $entry) {
        return $this->time($entry);
    }

    /**
     * Compile a timestamps entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestamps(Entry $entry) {
        return false;
    }

    /**
     * Compile a timestampsTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTz(Entry $entry) {
        return false;
    }

    /**
     * Compile a tinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function tinyInteger(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(-128, 127)');
    }

    /**
     * Compile a unsignedBigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedBigInteger(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 2 ** 64 -1)');
    }

    /**
     * Compile a unsignedDecimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedDecimal(Entry $entry) {
        return $this->float($entry);
    }

    /**
     * Compile a unsignedInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedInteger(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 4294967295)');
    }

    /**
     * Compile a unsignedMediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedMediumInteger(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 16777215)');
    }

    /**
     * Compile a unsignedSmallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedSmallInteger(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 65535)');
    }

    /**
     * Compile a unsignedTinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedTinyInteger(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(0, 255)');
    }

    /**
     * Compile a uuid entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function uuid(Entry $entry) {
        return $this->factoryGroup($entry->name(), '$faker->uuid');
    }

    /**
     * Compile a year entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function year(Entry $entry) {
        return $this->factoryGroup($entry->name(), 'mt_rand(1900, 2100)');
    }

}
