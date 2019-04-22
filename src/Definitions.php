<?php

namespace Bgaze\Crud;

/**
 * Description of EntriesDefinitions
 *
 * @author bgaze
 */
class Definitions {

    /**
     * The validation rules used against CRUD content inputs.
     */
    const VALIDATION = [
        'allowed' => 'array|min:1',
        'allowed.*' => 'table_column',
        'autoIncrement' => 'boolean',
        'column' => 'table_column',
        'columns' => 'array|min:1',
        'columns.*' => 'table_column',
        'comment' => 'nullable|string',
        'default' => 'nullable|string',
        'firstKey' => 'table_column',
        'foreignKey' => 'table_column',
        'foreignPivotKey' => 'table_column',
        'id' => 'table_column',
        'index' => 'boolean',
        'inverse' => 'boolean',
        'length' => 'nullable|integer',
        'localKey' => 'table_column',
        'name' => 'table_column',
        'nullable' => 'boolean',
        'ownerKey' => 'table_column',
        'parentKey' => 'table_column',
        'places' => 'integer',
        'related' => 'model_name',
        'relatedKey' => 'table_column',
        'relatedPivotKey' => 'table_column',
        'secondKey' => 'table_column',
        'secondLocalKey' => 'table_column',
        'table' => 'table_column',
        'through' => 'model_name',
        'total' => 'integer',
        'type' => 'model_name',
        'unique' => 'boolean',
        'unsigned' => 'boolean',
        'useCurrent' => 'boolean',
    ];

    /**
     * The list of date type columns.
     */
    const DATES = ['date', 'dateTime', 'dateTimeTz', 'time', 'timeTz', 'timestamp', 'timestampTz', 'year'];

    /**
     * The available modifiers availables for column entries.
     */
    const COLUMNS_MODIFIERS = [
        'default' => '->default(%value)',
        'comment' => '->comment(%value)',
        'nullable' => '->nullable()',
        'unsigned' => '->unsigned()',
        'index' => '->index()',
        'unique' => '->unique()'
    ];

    /*
     * The available relation entries.
     */
    const RELATIONS = [
        'hasOne' => '{related} {--f|foreignKey=} {--l|localKey=}',
        'hasMany' => '{related} {--f|foreignKey=} {--l|localKey=}',
        'belongsTo' => '{related} {--f|foreignKey=} {--o|ownerKey=}',
        'belongsToMany' => '{related} {--t|table=} {--f|foreignPivotKey=} {--r|relatedPivotKey=} {--p|parentKey=} {--l|relatedKey=}',
        'hasManyThrough' => '{related} {through} {--f|firstKey=} {--s|secondKey=} {--l|localKey=} {--r|secondLocalKey=}',
        'morphTo' => '{--n|name=} {--t|type=} {--i|id=}',
        'morphOne' => '{related} {name} {--t|type=} {--i|id=} {--l|localKey=}',
        'morphMany' => '{related} {name} {--t|type=} {--i|id=} {--l|localKey=}',
        'morphToMany' => '{related} {name} {--t|table=} {--f|foreignPivotKey=} {--r|relatedPivotKey=} {--p|parentKey=} {--l|relatedKey=} {--i|inverse}',
        'morphedByMany' => '{related} {name} {--t|table=} {--f|foreignPivotKey=} {--r|relatedPivotKey=} {--p|parentKey=} {--l|relatedKey=}',
    ];

    /*
     * The available indexes entries.
     */
    const INDEXES = [
        'index' => '{columns*}',
        'primaryIndex' => '{columns*}',
        'uniqueIndex' => '{columns*}',
        'spatialIndex' => '{columns*}',
    ];

    /*
     * The available columns entries.
     */
    const COLUMNS = [
        'bigInteger' => '{column} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'binary' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'boolean' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'char' => '{column} {length?} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'date' => '{column} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'dateTime' => '{column} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'dateTimeTz' => '{column} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'decimal' => '{column} {total=8} {places=2} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'double' => '{column} {total=8} {places=2} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'enum' => '{column} {allowed*} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'float' => '{column} {total=8} {places=2} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'geometry' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'geometryCollection' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'integer' => '{column} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'ipAddress' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'json' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'jsonb' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'lineString' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'longText' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'macAddress' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'mediumInteger' => '{column} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'mediumText' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'morphs' => '{name}',
        'multiLineString' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'multiPoint' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'multiPolygon' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'nullableMorphs' => '{column}',
        'point' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'polygon' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'rememberToken' => '',
        'smallInteger' => '{column} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'softDeletes' => '',
        'softDeletesTz' => '',
        'string' => '{column} {length?} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'text' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'time' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'timeTz' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'timestamp' => '{column} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'timestampTz' => '{column} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'timestamps' => '',
        'timestampsTz' => '',
        'tinyInteger' => '{column} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedBigInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedDecimal' => '{column} {total=8} {places=2} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedMediumInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedSmallInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedTinyInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'uuid' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'year' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
    ];

    /**
     * Get all available entries as a flat collection.
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function entries() {
        return collect(static::COLUMNS)->merge(static::RELATIONS)->merge(static::INDEXES);
    }

    /**
     * Get a signature by entry name.
     * 
     * @param string $name  The entry name
     * @return string       The entry signature
     * @throws \Exception
     */
    public static function get($name) {
        if (isset(static::COLUMNS[$name])) {
            return static::COLUMNS[$name];
        }

        if (isset(static::RELATIONS[$name])) {
            return static::RELATIONS[$name];
        }

        if (isset(static::INDEXES[$name])) {
            return static::INDEXES[$name];
        }

        throw new \Exception("Unknown entry type: {$name}");
    }

    /**
     * Get entry name, arguments and options as separated strings.
     * 
     * @param string $name  The entry name
     * @return array
     */
    public static function parse($name) {
        $signature = static::get($name);
        preg_match('/^((\s?\{[^-\}]+\})*)/', $signature, $a);
        preg_match('/((\{--[^\}]+\}\s?)*)$/', $signature, $o);
        return ['name' => $name, 'arguments' => $a[1], 'options' => $o[1]];
    }

    /**
     * Check if the entry is an index.
     * 
     * @param string $name  The entry name
     * @return boolean
     */
    public static function isIndex($name) {
        return isset(static::INDEXES[$name]);
    }

    /**
     * Check if the entry is a relation.
     * 
     * @param string $name  The entry name
     * @return boolean
     */
    public static function isRelation($name) {
        return isset(static::RELATIONS[$name]);
    }

    /**
     * Check if the entry is an index.
     * 
     * @param string $name  The entry name
     * @return boolean
     */
    public static function isDate($name) {
        return in_array($name, static::DATES);
    }

}
