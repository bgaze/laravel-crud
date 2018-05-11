<?php

return [
    'migrate' => [
        'validation' => [
            'column' => 'alpha_dash',
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
        'fields' => [
            'bigIncrements' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->bigIncrements(%column)',
                'description' => 'Add a bigIncrements field to table.',
            ],
            'bigInteger' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->bigInteger(%column)',
                'description' => 'Add a bigInteger field to table.',
            ],
            'binary' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->binary(%column)',
                'description' => 'Add a binary field to table.',
            ],
            'boolean' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->boolean(%column)',
                'description' => 'Add a boolean field to table.',
            ],
            'char' => [
                'signature' => '{column} {length?} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->char(%column, %length)',
                'description' => 'Add a char field to table.',
            ],
            'date' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->date(%column)',
                'description' => 'Add a date field to table.',
            ],
            'dateTime' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->dateTime(%column)',
                'description' => 'Add a dateTime field to table.',
            ],
            'dateTimeTz' => [
                'signature' => '{column} {--d|default=} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->dateTimeTz(%column)',
                'description' => 'Add a dateTimeTz field to table.',
            ],
            'decimal' => [
                'signature' => '{column} {total=8} {places=2} {--d|default=} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->decimal(%column, %total, %places)',
                'description' => 'Add a decimal field to table.',
            ],
            'double' => [
                'signature' => '{column} {total=8} {places=2} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->double(%column, %total, %places)',
                'description' => 'Add a double field to table.',
            ],
            'enum' => [
                'signature' => '{column} {allowed} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->enum(%column, %allowed)',
                'description' => 'Add a enum field to table.',
            ],
            'float' => [
                'signature' => '{column} {total=8} {places=2} {--d|default=} {--n|nullable} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->float(%column, %total, %places)',
                'description' => 'Add a float field to table.',
            ],
            'geometry' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->geometry(%column)',
                'description' => 'Add a geometry field to table.',
            ],
            'geometryCollection' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->geometryCollection(%column)',
                'description' => 'Add a geometryCollection field to table.',
            ],
            'increments' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->increments(%column)',
                'description' => 'Add a increments field to table.',
            ],
            'integer' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->integer(%column)',
                'description' => 'Add a integer field to table.',
            ],
            'ipAddress' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->ipAddress(%column)',
                'description' => 'Add a ipAddress field to table.',
            ],
            'json' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->json(%column)',
                'description' => 'Add a json field to table.',
            ],
            'jsonb' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->jsonb(%column)',
                'description' => 'Add a jsonb field to table.',
            ],
            'lineString' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->lineString(%column)',
                'description' => 'Add a lineString field to table.',
            ],
            'longText' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->longText(%column)',
                'description' => 'Add a longText field to table.',
            ],
            'macAddress' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->macAddress(%column)',
                'description' => 'Add a macAddress field to table.',
            ],
            'mediumIncrements' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->mediumIncrements(%column)',
                'description' => 'Add a mediumIncrements field to table.',
            ],
            'mediumInteger' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->mediumInteger(%column)',
                'description' => 'Add a mediumInteger field to table.',
            ],
            'mediumText' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->mediumText(%column)',
                'description' => 'Add a mediumText field to table.',
            ],
            'morphs' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->morphs(%column)',
                'description' => 'Add a morphs field to table.',
            ],
            'multiLineString' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->multiLineString(%column)',
                'description' => 'Add a multiLineString field to table.',
            ],
            'multiPoint' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->multiPoint(%column)',
                'description' => 'Add a multiPoint field to table.',
            ],
            'multiPolygon' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->multiPolygon(%column)',
                'description' => 'Add a multiPolygon field to table.',
            ],
            'nullableMorphs' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->nullableMorphs(%column)',
                'description' => 'Add a nullableMorphs field to table.',
            ],
            'point' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->point(%column)',
                'description' => 'Add a point field to table.',
            ],
            'polygon' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->polygon(%column)',
                'description' => 'Add a polygon field to table.',
            ],
            'smallIncrements' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->smallIncrements(%column)',
                'description' => 'Add a smallIncrements field to table.',
            ],
            'smallInteger' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->smallInteger(%column)',
                'description' => 'Add a smallInteger field to table.',
            ],
            'string' => [
                'signature' => '{column} {length?} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->string(%column, %length)',
                'description' => 'Add a string field to table.',
            ],
            'text' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->text(%column)',
                'description' => 'Add a text field to table.',
            ],
            'time' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->time(%column)',
                'description' => 'Add a time field to table.',
            ],
            'timeTz' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->timeTz(%column)',
                'description' => 'Add a timeTz field to table.',
            ],
            'timestamp' => [
                'signature' => '{column} {--d|default=} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->timestamp(%column)',
                'description' => 'Add a timestamp field to table.',
            ],
            'timestampTz' => [
                'signature' => '{column} {--d|default=} {--u|useCurrent} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->timestampTz(%column)',
                'description' => 'Add a timestampTz field to table.',
            ],
            'tinyIncrements' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->tinyIncrements(%column)',
                'description' => 'Add a tinyIncrements field to table.',
            ],
            'tinyInteger' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->tinyInteger(%column)',
                'description' => 'Add a tinyInteger field to table.',
            ],
            'unsignedBigInteger' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->unsignedBigInteger(%column)',
                'description' => 'Add a unsignedBigInteger field to table.',
            ],
            'unsignedDecimal' => [
                'signature' => '{column} {total=8} {places=2} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->unsignedDecimal(%column, %total, %places)',
                'description' => 'Add a unsignedDecimal field to table.',
            ],
            'unsignedInteger' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->unsignedInteger(%column)',
                'description' => 'Add a unsignedInteger field to table.',
            ],
            'unsignedMediumInteger' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->unsignedMediumInteger(%column)',
                'description' => 'Add a unsignedMediumInteger field to table.',
            ],
            'unsignedSmallInteger' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->unsignedSmallInteger(%column)',
                'description' => 'Add a unsignedSmallInteger field to table.',
            ],
            'unsignedTinyInteger' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--a|autoIncrement} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->unsignedTinyInteger(%column)',
                'description' => 'Add a unsignedTinyInteger field to table.',
            ],
            'uuid' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->uuid(%column)',
                'description' => 'Add a uuid field to table.',
            ],
            'year' => [
                'signature' => '{column} {--d|default=} {--n|nullable} {--i|index} {--q|unique} {--c|comment=}',
                'template' => '$table->year(%column)',
                'description' => 'Add a year field to table.',
            ],
        ],
        'timestamps' => [
            'nullableTimestamps' => [
                'signature' => 'nullableTimestamps',
                'template' => '$table->timestampsTz();',
                'description' => 'Add a nullableTimestamps field to table.',
            ],
            'timestamps' => [
                'signature' => 'timestamps',
                'template' => '$table->timestamps();',
                'description' => 'Add a timestamps field to table.',
            ],
            'timestampsTz' => [
                'signature' => 'timestampsTz',
                'template' => '$table->timestampsTz();',
                'description' => 'Add a timestampsTz field to table.',
            ],
        ],
        'softDeletes' => [
            'softDeletes' => [
                'signature' => 'softDeletes',
                'template' => '$table->softDeletes();',
                'description' => 'Add a softDeletes field to table.',
            ],
            'softDeletesTz' => [
                'signature' => 'softDeletesTz',
                'template' => '$table->softDeletesTz();',
                'description' => 'Add a softDeletesTz field to table.',
            ],
        ],
        'indexes' => [
            'primary' => '$table->primary(%columns);',
            'unique' => '$table->unique(%columns);',
            'index' => '$table->index(%columns);',
            'spatialIndex' => '$table->spatialIndex(%columns);',
        ],
        'modifiers' => [
            'default' => '->default(%value)',
            'comment' => '->comment(%value)',
            'nullable' => '->nullable()',
            'unsigned' => '->unsigned()',
            'index' => '->index()',
            'unique' => '->unique()'
        ],
    ],
];

