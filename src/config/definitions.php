<?php

/*
  |---------------------------------------------------------------------------
  | CRUD Definitions
  |---------------------------------------------------------------------------
  |
  | This is a dictionnary for CRUD internal use.
  | This file should not be published or modified.
  |
 */

return [
    /*
      |---------------------------------------------------------------------------
      | Validation rules for fields parameters and options
      |---------------------------------------------------------------------------
     */
    'validation' => [
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
    ],
    /*
      |---------------------------------------------------------------------------
      | Timestamps templates
      |---------------------------------------------------------------------------
     */
    'timestamps' => [
        'timestamps' => '$table->timestamps();',
        'timestampsTz' => '$table->timestampsTz();',
        'nullableTimestamps' => '$table->timestampsTz();',
    ],
    /*
      |---------------------------------------------------------------------------
      | Soft deletes templates
      |---------------------------------------------------------------------------
     */
    'softDeletes' => [
        'softDeletes' => '$table->softDeletes();',
        'softDeletesTz' => '$table->softDeletesTz();',
    ],
    /*
      |---------------------------------------------------------------------------
      | Modifiers templates
      |---------------------------------------------------------------------------
     */
    'modifiers' => [
        'default' => '->default(%value)',
        'comment' => '->comment(%value)',
        'nullable' => '->nullable()',
        'unsigned' => '->unsigned()',
        'index' => '->index()',
        'unique' => '->unique()'
    ],
    /*
      |---------------------------------------------------------------------------
      | Relations list
      |---------------------------------------------------------------------------
     */
    'relations' => [
        'hasOne',
        'hasMany',
        'belongsTo',
        'belongsToMany',
        'hasManyThrough',
        'morphTo',
        'morphOne',
        'morphMany',
        'morphToMany',
        'morphedByMany',
    ],
    /*
      |---------------------------------------------------------------------------
      | Indexes list
      |---------------------------------------------------------------------------
     */
    'indexes' => [
        'index',
        'primaryIndex',
        'uniqueIndex',
        'spatialIndex',
    ],
    /*
      |---------------------------------------------------------------------------
      | Validation rules for fields parameters
      |---------------------------------------------------------------------------
     */
    'fields' => [
        // Regular fields.
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
        'morphs' => '{column}',
        'multiLineString' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'multiPoint' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'multiPolygon' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'nullableMorphs' => '{column}',
        'point' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'polygon' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'smallInteger' => '{column} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'string' => '{column} {length?} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'text' => '{column} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
        'time' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'timeTz' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'timestamp' => '{column} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'timestampTz' => '{column} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'tinyInteger' => '{column} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedBigInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedDecimal' => '{column} {total=8} {places=2} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedMediumInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedSmallInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'unsignedTinyInteger' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'uuid' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        'year' => '{column} {--n|nullable} {--i|index} {--q|unique} {--d|default=} {--c|comment=}',
        // Relations.
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
        // Indexes.
        'index' => '{columns*}',
        'primaryIndex' => '{columns*}',
        'uniqueIndex' => '{columns*}',
        'spatialIndex' => '{columns*}',
    ],
];

