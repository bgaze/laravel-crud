<?php

namespace Bgaze\Crud\Core;

use Bgaze\Crud\Definitions;

/**
 * A trait for entries templates generation.
 * 
 * @author bgaze <benjamin@bgaze.fr>
 * 
 * FIELDS
 * 
 * @method string bigInteger(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a bigInteger column
 * @method string binary(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a binary column
 * @method string boolean(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a boolean column
 * @method string char(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a char column
 * @method string date(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a date column
 * @method string dateTime(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a dateTime column
 * @method string dateTimeTz(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a dateTimeTz column
 * @method string decimal(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a decimal column
 * @method string double(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a double column
 * @method string enum(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an enum column
 * @method string float(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a float column
 * @method string geometry(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a geometry column
 * @method string geometryCollection(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a geometryCollection column
 * @method string integer(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an integer column
 * @method string ipAddress(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an ipAddress column
 * @method string json(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a json column
 * @method string jsonb(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a jsonb column
 * @method string lineString(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a lineString column
 * @method string longText(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a longText column
 * @method string macAddress(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a macAddress column
 * @method string mediumInteger(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a mediumInteger column
 * @method string mediumText(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a mediumText column
 * @method string morphs(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a morphs column
 * @method string multiLineString(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a multiLineString column
 * @method string multiPoint(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a multiPoint column
 * @method string multiPolygon(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a multiPolygon column
 * @method string nullableMorphs(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a nullableMorphs column
 * @method string point(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a point column
 * @method string polygon(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a polygon column
 * @method string rememberToken(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a rememberToken column
 * @method string smallInteger(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a smallInteger column
 * @method string softDeletes(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a softDeletes column
 * @method string softDeletesTz(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a softDeletesTz column
 * @method string string(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a string column
 * @method string text(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a text column
 * @method string time(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a time column
 * @method string timeTz(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a timeTz column
 * @method string timestamp(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a timestamp column
 * @method string timestampTz(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a timestampTz column
 * @method string timestamps(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a timestamps column
 * @method string timestampsTz(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a timestampsTz column
 * @method string tinyInteger(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a tinyInteger column
 * @method string unsignedBigInteger(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an unsignedBigInteger column
 * @method string unsignedDecimal(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an unsignedDecimal column
 * @method string unsignedInteger(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an unsignedInteger column
 * @method string unsignedMediumInteger(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an unsignedMediumInteger column
 * @method string unsignedSmallInteger(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an unsignedSmallInteger column
 * @method string unsignedTinyInteger(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an unsignedTinyInteger column
 * @method string uuid(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an uuid column
 * @method string year(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a year column
 * 
 * RELATIONS
 * 
 * @method string hasOne(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a hasOne relation
 * @method string hasMany(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a hasMany relation
 * @method string belongsTo(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a belongsTo relation
 * @method string belongsToMany(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a belongsToMany relation
 * @method string hasManyThrough(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a hasManyThrough relation
 * @method string morphTo(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a morphTo relation
 * @method string morphOne(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a morphOne relation
 * @method string morphMany(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a morphMany relation
 * @method string morphToMany(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a morphToMany relation
 * @method string morphedByMany(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a morphedByMany relation
 * 
 * INDEXES
 * 
 * @method string index(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an index
 * @method string primaryIndex(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a primaryIndex index
 * @method string uniqueIndex(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile an uniqueIndex index
 * @method string spatialIndex(\Bgaze\Crud\Core\Entry $entry The entry to compile) Compile a spatialIndex index

 */
trait EntryCompilerTrait {

    /**
     * Get a template for a entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function compile(Entry $entry) {
        return $this->{$entry->command()}($entry);
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

        if (!preg_match('/^(' . $methods . ')$/', $method)) {
            throw new \BadMethodCallException("Method {$method} does not exist.");
        }

        return call_user_func_array([$this, 'compileDefault'], $parameters);
    }

    /**
     * Get the default compilation function for an entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The compiled entry
     */
    abstract public function compileDefault(Entry $entry);
}
