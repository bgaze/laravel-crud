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
        'column' => 'alpha_dash',
        'columns' => 'array|min:1',
        'columns.*' => 'alpha_dash',
        'total' => 'integer',
        'places' => 'integer',
        'length' => 'nullable|integer',
        'allowed' => 'array|min:1',
        'allowed.*' => 'alpha_dash',
        'nullable' => 'boolean',
        'autoIncrement' => 'boolean',
        'unsigned' => 'boolean',
        'unique' => 'boolean',
        'default' => 'nullable|string',
        'comment' => 'nullable|string'
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
      | Validation rules for fields parameters
      |---------------------------------------------------------------------------
     */
    'fields' => [
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
        'index' => '{columns*}',
        'primaryIndex' => '{columns*}',
        'uniqueIndex' => '{columns*}',
        'spatialIndex' => '{columns*}',
    ],
];

