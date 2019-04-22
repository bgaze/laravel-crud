<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Bgaze\Crud\Core;

use Bgaze\Crud\Definitions;

/**
 * A trait for entries templates generation.
 * 
 * @author bgaze <benjamin@bgaze.fr>
 * 
 * FIELDS
 * 
 * @method string bigIntegerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a bigInteger entry
 * @method string binaryTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a binary entry
 * @method string booleanTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a boolean entry
 * @method string charTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a char entry
 * @method string dateTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a date entry
 * @method string dateTimeTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a dateTime entry
 * @method string dateTimeTzTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a dateTimeTz entry
 * @method string decimalTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a decimal entry
 * @method string doubleTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a double entry
 * @method string enumTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a enum entry
 * @method string floatTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a float entry
 * @method string geometryTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a geometry entry
 * @method string geometryCollectionTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a geometryCollection entry
 * @method string integerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a integer entry
 * @method string ipAddressTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a ipAddress entry
 * @method string jsonTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a json entry
 * @method string jsonbTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a jsonb entry
 * @method string lineStringTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a lineString entry
 * @method string longTextTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a longText entry
 * @method string macAddressTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a macAddress entry
 * @method string mediumIntegerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a mediumInteger entry
 * @method string mediumTextTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a mediumText entry
 * @method string morphsTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a morphs entry
 * @method string multiLineStringTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a multiLineString entry
 * @method string multiPointTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a multiPoint entry
 * @method string multiPolygonTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a multiPolygon entry
 * @method string nullableMorphsTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a nullableMorphs entry
 * @method string pointTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a point entry
 * @method string polygonTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a polygon entry
 * @method string rememberTokenTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a rememberToken entry
 * @method string smallIntegerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a smallInteger entry
 * @method string softDeletesTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a softDeletes entry
 * @method string softDeletesTzTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a softDeletesTz entry
 * @method string stringTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a string entry
 * @method string textTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a text entry
 * @method string timeTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a time entry
 * @method string timeTzTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a timeTz entry
 * @method string timestampTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a timestamp entry
 * @method string timestampTzTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a timestampTz entry
 * @method string tinyIntegerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a tinyInteger entry
 * @method string unsignedBigIntegerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a unsignedBigInteger entry
 * @method string unsignedDecimalTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a unsignedDecimal entry
 * @method string unsignedIntegerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a unsignedInteger entry
 * @method string unsignedMediumIntegerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a unsignedMediumInteger entry
 * @method string unsignedSmallIntegerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a unsignedSmallInteger entry
 * @method string unsignedTinyIntegerTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a unsignedTinyInteger entry
 * @method string uuidTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a uuid entry
 * @method string yearTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a year entry
 * 
 * RELATIONS
 * 
 * @method string hasOneTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a hasOne entry
 * @method string hasManyTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a hasMany entry
 * @method string belongsToTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a belongsTo entry
 * @method string belongsToManyTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a belongsToMany entry
 * @method string hasManyThroughTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a hasManyThrough entry
 * @method string morphToTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a morphTo entry
 * @method string morphOneTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a morphOne entry
 * @method string morphManyTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a morphMany entry
 * @method string morphToManyTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a morphToMany entry
 * @method string morphedByManyTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a morphedByMany entry
 * 
 * INDEXES
 * 
 * @method string indexTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a index entry
 * @method string primaryIndexTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a primaryIndex entry
 * @method string uniqueIndexTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a uniqueIndex entry
 * @method string spatialIndexTemplate(\Bgaze\Crud\Core\Entry $entry The entry to compile) Get the template for a spatialIndex entry

 */
trait EntriesTemplatesTrait {

    /**
     * Get a template for a entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    protected function entryTemplate(Entry $entry) {
        $method = $entry->command() . 'Template';
        return $this->{$method}($entry);
    }

    /**
     * Use default template for all existing entries if method is not defined.
     * 
     * @param string $method        The entry method name.
     * @param array $parameters     The parameters passed to the method.
     * 
     * @return string   The template for the entry
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters) {
        $methods = Definitions::entries()->keys()->implode('|');

        if (!preg_match('/^(' . $methods . ')Template$/', $method)) {
            throw new \BadMethodCallException("Method {$method} does not exist.");
        }

        return call_user_func_array([$this, 'defaultTemplate'], $parameters);
    }

    /**
     * Get the default template for a entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    abstract public function defaultTemplate(Entry $entry);
}
