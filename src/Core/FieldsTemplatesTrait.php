<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Bgaze\Crud\Core;

/**
 *
 * @author bgaze
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
     * Get the default template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    abstract public function defaultTemplate(Field $field);

    /**
     * Get the template for a bigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function bigIntegerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a binary field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function binaryTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a boolean field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function booleanTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a char field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function charTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a date field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function dateTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a dateTime field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function dateTimeTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a dateTimeTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function dateTimeTzTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a decimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function decimalTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a double field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function doubleTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a enum field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function enumTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a float field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function floatTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a geometry field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function geometryTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a geometryCollection field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function geometryCollectionTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a integer field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function integerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a ipAddress field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function ipAddressTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a json field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function jsonTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a jsonb field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function jsonbTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a lineString field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function lineStringTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a longText field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function longTextTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a macAddress field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function macAddressTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a mediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function mediumIntegerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a mediumText field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function mediumTextTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a morphs field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function morphsTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a multiLineString field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function multiLineStringTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a multiPoint field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function multiPointTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a multiPolygon field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function multiPolygonTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a nullableMorphs field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function nullableMorphsTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a point field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function pointTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a polygon field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function polygonTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a smallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function smallIntegerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a string field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function stringTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a text field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function textTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a time field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timeTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a timeTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timeTzTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a timestamp field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a timestampTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampTzTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a tinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function tinyIntegerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a unsignedBigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedBigIntegerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a unsignedDecimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedDecimalTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a unsignedInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedIntegerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a unsignedMediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedMediumIntegerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a unsignedSmallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedSmallIntegerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a unsignedTinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedTinyIntegerTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a uuid field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function uuidTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a year field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function yearTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a index field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function indexTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a primaryIndex field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function primaryIndexTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a uniqueIndex field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function uniqueIndexTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

    /**
     * Get the template for a spatialIndex field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function spatialIndexTemplate(Field $field) {
        return $this->defaultTemplate($field);
    }

}
