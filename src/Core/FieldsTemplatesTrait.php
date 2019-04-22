<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Bgaze\Crud\Core;

use Bgaze\Crud\Definitions;

/**
 * A trait for fields templates generation.
 * 
 * @author bgaze <benjamin@bgaze.fr>
 * 
 * FIELDS
 * 
 * @method string bigIntegerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a bigInteger field
 * @method string binaryTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a binary field
 * @method string booleanTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a boolean field
 * @method string charTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a char field
 * @method string dateTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a date field
 * @method string dateTimeTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a dateTime field
 * @method string dateTimeTzTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a dateTimeTz field
 * @method string decimalTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a decimal field
 * @method string doubleTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a double field
 * @method string enumTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a enum field
 * @method string floatTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a float field
 * @method string geometryTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a geometry field
 * @method string geometryCollectionTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a geometryCollection field
 * @method string integerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a integer field
 * @method string ipAddressTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a ipAddress field
 * @method string jsonTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a json field
 * @method string jsonbTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a jsonb field
 * @method string lineStringTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a lineString field
 * @method string longTextTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a longText field
 * @method string macAddressTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a macAddress field
 * @method string mediumIntegerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a mediumInteger field
 * @method string mediumTextTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a mediumText field
 * @method string morphsTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphs field
 * @method string multiLineStringTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a multiLineString field
 * @method string multiPointTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a multiPoint field
 * @method string multiPolygonTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a multiPolygon field
 * @method string nullableMorphsTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a nullableMorphs field
 * @method string pointTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a point field
 * @method string polygonTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a polygon field
 * @method string rememberTokenTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a rememberToken field
 * @method string smallIntegerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a smallInteger field
 * @method string softDeletesTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a softDeletes field
 * @method string softDeletesTzTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a softDeletesTz field
 * @method string stringTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a string field
 * @method string textTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a text field
 * @method string timeTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a time field
 * @method string timeTzTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a timeTz field
 * @method string timestampTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a timestamp field
 * @method string timestampTzTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a timestampTz field
 * @method string tinyIntegerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a tinyInteger field
 * @method string unsignedBigIntegerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedBigInteger field
 * @method string unsignedDecimalTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedDecimal field
 * @method string unsignedIntegerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedInteger field
 * @method string unsignedMediumIntegerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedMediumInteger field
 * @method string unsignedSmallIntegerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedSmallInteger field
 * @method string unsignedTinyIntegerTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a unsignedTinyInteger field
 * @method string uuidTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a uuid field
 * @method string yearTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a year field
 * 
 * RELATIONS
 * 
 * @method string hasOneTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a hasOne field
 * @method string hasManyTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a hasMany field
 * @method string belongsToTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a belongsTo field
 * @method string belongsToManyTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a belongsToMany field
 * @method string hasManyThroughTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a hasManyThrough field
 * @method string morphToTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphTo field
 * @method string morphOneTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphOne field
 * @method string morphManyTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphMany field
 * @method string morphToManyTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphToMany field
 * @method string morphedByManyTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a morphedByMany field
 * 
 * INDEXES
 * 
 * @method string indexTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a index field
 * @method string primaryIndexTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a primaryIndex field
 * @method string uniqueIndexTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a uniqueIndex field
 * @method string spatialIndexTemplate(\Bgaze\Crud\Core\Field $field The field to compile) Get the template for a spatialIndex field

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
        $methods = Definitions::entries()->keys()->implode('|');

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
