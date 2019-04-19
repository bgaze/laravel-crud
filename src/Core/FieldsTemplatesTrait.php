<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Bgaze\Crud\Core;

/**
 * A trait for fields templates generation.
 * 
 * @author bgaze <benjamin@bgaze.fr>
 * 
 * FIELDS
 * 
 * @method string bigInteger(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a bigInteger field
 * @method string binary(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a binary field
 * @method string boolean(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a boolean field
 * @method string char(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a char field
 * @method string date(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a date field
 * @method string dateTime(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a dateTime field
 * @method string dateTimeTz(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a dateTimeTz field
 * @method string decimal(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a decimal field
 * @method string double(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a double field
 * @method string enum(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a enum field
 * @method string float(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a float field
 * @method string geometry(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a geometry field
 * @method string geometryCollection(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a geometryCollection field
 * @method string integer(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a integer field
 * @method string ipAddress(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a ipAddress field
 * @method string json(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a json field
 * @method string jsonb(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a jsonb field
 * @method string lineString(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a lineString field
 * @method string longText(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a longText field
 * @method string macAddress(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a macAddress field
 * @method string mediumInteger(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a mediumInteger field
 * @method string mediumText(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a mediumText field
 * @method string morphs(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphs field
 * @method string multiLineString(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a multiLineString field
 * @method string multiPoint(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a multiPoint field
 * @method string multiPolygon(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a multiPolygon field
 * @method string nullableMorphs(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a nullableMorphs field
 * @method string point(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a point field
 * @method string polygon(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a polygon field
 * @method string smallInteger(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a smallInteger field
 * @method string string(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a string field
 * @method string text(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a text field
 * @method string time(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a time field
 * @method string timeTz(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a timeTz field
 * @method string timestamp(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a timestamp field
 * @method string timestampTz(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a timestampTz field
 * @method string tinyInteger(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a tinyInteger field
 * @method string unsignedBigInteger(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedBigInteger field
 * @method string unsignedDecimal(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedDecimal field
 * @method string unsignedInteger(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedInteger field
 * @method string unsignedMediumInteger(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedMediumInteger field
 * @method string unsignedSmallInteger(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedSmallInteger field
 * @method string unsignedTinyInteger(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedTinyInteger field
 * @method string uuid(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a uuid field
 * @method string year(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a year field
 * 
 * RELATIONS
 * 
 * @method string hasOne(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a hasOne field
 * @method string hasMany(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a hasMany field
 * @method string belongsTo(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a belongsTo field
 * @method string belongsToMany(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a belongsToMany field
 * @method string hasManyThrough(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a hasManyThrough field
 * @method string morphTo(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphTo field
 * @method string morphOne(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphOne field
 * @method string morphMany(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphMany field
 * @method string morphToMany(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphToMany field
 * @method string morphedByMany(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphedByMany field
 * 
 * INDEXES
 * 
 * @method string index(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a index field
 * @method string primaryIndex(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a primaryIndex field
 * @method string uniqueIndex(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a uniqueIndex field
 * @method string spatialIndex(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a spatialIndex field

 */
trait FieldsTemplatesTrait {

    /**
     * Get a template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    protected function fieldTemplate(Field $field) {
        $method = $field->command() . 'Template';
        return $this->{$method}($field);
    }

    /**
     * Use default template for all existing fields if method is not defined.
     * 
     * @param string $method        The field method name.
     * @param array $parameters     The parameters passed to the method.
     * 
     * @return string   The template for the field
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters) {
        $methods = implode('|', array_keys(config('crud-definitions.fields')));
        if (!preg_match('/^(' . $methods . ')Template$/', $method)) {
            throw new \BadMethodCallException("Method {$method} does not exist.");
        }

        return call_user_func_array([$this, 'defaultTemplate'], $parameters);
    }

    /**
     * Get the default template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    abstract public function defaultTemplate(Field $field);
}
