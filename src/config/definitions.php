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
    'validation' => [
        'column' => 'alpha_dash',
        'columns' => 'array|size:1',
        'columns.*' => 'alpha',
        'total' => 'integer',
        'places' => 'integer',
        'length' => 'nullable|integer',
        'allowed' => 'array|size:1',
        'allowed.*' => 'alpha_dash',
        'nullable' => 'boolean',
        'autoIncrement' => 'boolean',
        'unsigned' => 'boolean',
        'unique' => 'boolean',
        'default' => 'nullable|string',
        'comment' => 'nullable|string'
    ],
    'timestamps' => [
        'nullableTimestamps' => '$table->timestampsTz();',
        'timestamps' => '$table->timestamps();',
        'timestampsTz' => '$table->timestampsTz();',
    ],
    'softDeletes' => [
        'softDeletes' => '$table->softDeletes();',
        'softDeletesTz' => '$table->softDeletesTz();',
    ],
    'modifiers' => [
        'default' => '->default(%value)',
        'comment' => '->comment(%value)',
        'nullable' => '->nullable()',
        'unsigned' => '->unsigned()',
        'index' => '->index()',
        'unique' => '->unique()'
    ],
    'fields' => [
        'bigIncrements' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->bigIncrements(%column)',
            'description' => 'Add a bigIncrements field to table.',
            'type' => 'string',
        ],
        'bigInteger' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->bigInteger(%column)',
            'description' => 'Add a bigInteger field to table.',
            'type' => 'string',
        ],
        'binary' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->binary(%column)',
            'description' => 'Add a binary field to table.',
            'type' => 'string',
        ],
        'boolean' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->boolean(%column)',
            'description' => 'Add a boolean field to table.',
            'type' => 'string',
        ],
        'char' => [
            'signature' => '{column} {length?} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->char(%column, %length)',
            'description' => 'Add a char field to table.',
            'type' => 'string',
        ],
        'date' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->date(%column)',
            'description' => 'Add a date field to table.',
            'type' => 'string',
        ],
        'dateTime' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->dateTime(%column)',
            'description' => 'Add a dateTime field to table.',
            'type' => 'string',
        ],
        'dateTimeTz' => [
            'signature' => '{column} {--d|default=} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->dateTimeTz(%column)',
            'description' => 'Add a dateTimeTz field to table.',
            'type' => 'string',
        ],
        'decimal' => [
            'signature' => '{column} {total=8} {places=2} {--d|default=} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->decimal(%column, %total, %places)',
            'description' => 'Add a decimal field to table.',
            'type' => 'string',
        ],
        'double' => [
            'signature' => '{column} {total=8} {places=2} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->double(%column, %total, %places)',
            'description' => 'Add a double field to table.',
            'type' => 'string',
        ],
        'enum' => [
            'signature' => '{column} {allowed*} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->enum(%column, %allowed)',
            'description' => 'Add a enum field to table.',
            'type' => 'string',
        ],
        'float' => [
            'signature' => '{column} {total=8} {places=2} {--d|default=} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->float(%column, %total, %places)',
            'description' => 'Add a float field to table.',
            'type' => 'string',
        ],
        'geometry' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->geometry(%column)',
            'description' => 'Add a geometry field to table.',
            'type' => 'string',
        ],
        'geometryCollection' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->geometryCollection(%column)',
            'description' => 'Add a geometryCollection field to table.',
            'type' => 'string',
        ],
        'increments' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->increments(%column)',
            'description' => 'Add a increments field to table.',
            'type' => 'string',
        ],
        'integer' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->integer(%column)',
            'description' => 'Add a integer field to table.',
            'type' => 'string',
        ],
        'ipAddress' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->ipAddress(%column)',
            'description' => 'Add a ipAddress field to table.',
            'type' => 'string',
        ],
        'json' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->json(%column)',
            'description' => 'Add a json field to table.',
            'type' => 'string',
        ],
        'jsonb' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->jsonb(%column)',
            'description' => 'Add a jsonb field to table.',
            'type' => 'string',
        ],
        'lineString' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->lineString(%column)',
            'description' => 'Add a lineString field to table.',
            'type' => 'string',
        ],
        'longText' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->longText(%column)',
            'description' => 'Add a longText field to table.',
            'type' => 'string',
        ],
        'macAddress' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->macAddress(%column)',
            'description' => 'Add a macAddress field to table.',
            'type' => 'string',
        ],
        'mediumIncrements' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->mediumIncrements(%column)',
            'description' => 'Add a mediumIncrements field to table.',
            'type' => 'string',
        ],
        'mediumInteger' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->mediumInteger(%column)',
            'description' => 'Add a mediumInteger field to table.',
            'type' => 'string',
        ],
        'mediumText' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->mediumText(%column)',
            'description' => 'Add a mediumText field to table.',
            'type' => 'string',
        ],
        'morphs' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->morphs(%column)',
            'description' => 'Add a morphs field to table.',
            'type' => 'string',
        ],
        'multiLineString' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->multiLineString(%column)',
            'description' => 'Add a multiLineString field to table.',
            'type' => 'string',
        ],
        'multiPoint' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->multiPoint(%column)',
            'description' => 'Add a multiPoint field to table.',
            'type' => 'string',
        ],
        'multiPolygon' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->multiPolygon(%column)',
            'description' => 'Add a multiPolygon field to table.',
            'type' => 'string',
        ],
        'nullableMorphs' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->nullableMorphs(%column)',
            'description' => 'Add a nullableMorphs field to table.',
            'type' => 'string',
        ],
        'point' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->point(%column)',
            'description' => 'Add a point field to table.',
            'type' => 'string',
        ],
        'polygon' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->polygon(%column)',
            'description' => 'Add a polygon field to table.',
            'type' => 'string',
        ],
        'smallIncrements' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->smallIncrements(%column)',
            'description' => 'Add a smallIncrements field to table.',
            'type' => 'string',
        ],
        'smallInteger' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->smallInteger(%column)',
            'description' => 'Add a smallInteger field to table.',
            'type' => 'string',
        ],
        'string' => [
            'signature' => '{column} {length?} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->string(%column, %length)',
            'description' => 'Add a string field to table.',
            'type' => 'string',
        ],
        'text' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->text(%column)',
            'description' => 'Add a text field to table.',
            'type' => 'string',
        ],
        'time' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->time(%column)',
            'description' => 'Add a time field to table.',
            'type' => 'string',
        ],
        'timeTz' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->timeTz(%column)',
            'description' => 'Add a timeTz field to table.',
            'type' => 'string',
        ],
        'timestamp' => [
            'signature' => '{column} {--d|default=} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->timestamp(%column)',
            'description' => 'Add a timestamp field to table.',
            'type' => 'string',
        ],
        'timestampTz' => [
            'signature' => '{column} {--d|default=} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->timestampTz(%column)',
            'description' => 'Add a timestampTz field to table.',
            'type' => 'string',
        ],
        'tinyIncrements' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->tinyIncrements(%column)',
            'description' => 'Add a tinyIncrements field to table.',
            'type' => 'string',
        ],
        'tinyInteger' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->tinyInteger(%column)',
            'description' => 'Add a tinyInteger field to table.',
            'type' => 'string',
        ],
        'unsignedBigInteger' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->unsignedBigInteger(%column)',
            'description' => 'Add a unsignedBigInteger field to table.',
            'type' => 'string',
        ],
        'unsignedDecimal' => [
            'signature' => '{column} {total=8} {places=2} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->unsignedDecimal(%column, %total, %places)',
            'description' => 'Add a unsignedDecimal field to table.',
            'type' => 'string',
        ],
        'unsignedInteger' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->unsignedInteger(%column)',
            'description' => 'Add a unsignedInteger field to table.',
            'type' => 'string',
        ],
        'unsignedMediumInteger' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->unsignedMediumInteger(%column)',
            'description' => 'Add a unsignedMediumInteger field to table.',
            'type' => 'string',
        ],
        'unsignedSmallInteger' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->unsignedSmallInteger(%column)',
            'description' => 'Add a unsignedSmallInteger field to table.',
            'type' => 'string',
        ],
        'unsignedTinyInteger' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->unsignedTinyInteger(%column)',
            'description' => 'Add a unsignedTinyInteger field to table.',
            'type' => 'string',
        ],
        'uuid' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->uuid(%column)',
            'description' => 'Add a uuid field to table.',
            'type' => 'string',
        ],
        'year' => [
            'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
            'template' => '$table->year(%column)',
            'description' => 'Add a year field to table.',
            'type' => 'string',
        ],
        'index' => [
            'signature' => '{columns*}',
            'template' => '$table->index(%columns)',
            'description' => 'Add an index to table.',
            'type' => 'index',
        ],
        'primaryIndex' => [
            'signature' => '{columns*}',
            'template' => '$table->primary(%columns)',
            'description' => 'Add a primary index to table.',
            'type' => 'index',
        ],
        'uniqueIndex' => [
            'signature' => '{columns*}',
            'template' => '$table->unique(%columns)',
            'description' => 'Add an unique index to table.',
            'type' => 'index',
        ],
        'spatialIndex' => [
            'signature' => '{columns*}',
            'template' => '$table->spatialIndex(%columns)',
            'description' => 'Add a spatial index to table.',
            'type' => 'index',
        ],
    ],
];

