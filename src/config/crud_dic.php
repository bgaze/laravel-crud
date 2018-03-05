<?php

return [
    'migrate' => [
        'fields' => [
            'bigIncrements' => [
                'signature' => 'bigIncrements {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->bigIncrements(%column)',
            ],
            'bigInteger' => [
                'signature' => 'bigInteger {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->bigInteger(%column)',
            ],
            'binary' => [
                'signature' => 'binary {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->binary(%column)',
            ],
            'boolean' => [
                'signature' => 'boolean {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->boolean(%column)',
            ],
            'char' => [
                'signature' => 'char {column} {length?} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->char(%column, %length)',
            ],
            'date' => [
                'signature' => 'date {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->date(%column)',
            ],
            'dateTime' => [
                'signature' => 'dateTime {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->dateTime(%column)',
            ],
            'dateTimeTz' => [
                'signature' => 'dateTimeTz {column} {--d|default=} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->dateTimeTz(%column)',
            ],
            'decimal' => [
                'signature' => 'decimal {column} {total=8} {places=2} {--d|default=} {--nul|nullable} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->decimal(%column, %total, %places)',
            ],
            'double' => [
                'signature' => 'double {column} {total=8} {places=2} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->double(%column, %total, %places)',
            ],
            'enum' => [
                'signature' => 'enum {column} {allowed} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->enum(%column, %allowed)',
            ],
            'float' => [
                'signature' => 'float {column} {total=8} {places=2} {--d|default=} {--nul|nullable} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->float(%column, %total, %places)',
            ],
            'geometry' => [
                'signature' => 'geometry {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->geometry(%column)',
            ],
            'geometryCollection' => [
                'signature' => 'geometryCollection {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->geometryCollection(%column)',
            ],
            'increments' => [
                'signature' => 'increments {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->increments(%column)',
            ],
            'integer' => [
                'signature' => 'integer {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->integer(%column)',
            ],
            'ipAddress' => [
                'signature' => 'ipAddress {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->ipAddress(%column)',
            ],
            'json' => [
                'signature' => 'json {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->json(%column)',
            ],
            'jsonb' => [
                'signature' => 'jsonb {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->jsonb(%column)',
            ],
            'lineString' => [
                'signature' => 'lineString {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->lineString(%column)',
            ],
            'longText' => [
                'signature' => 'longText {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->longText(%column)',
            ],
            'macAddress' => [
                'signature' => 'macAddress {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->macAddress(%column)',
            ],
            'mediumIncrements' => [
                'signature' => 'mediumIncrements {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->mediumIncrements(%column)',
            ],
            'mediumInteger' => [
                'signature' => 'mediumInteger {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->mediumInteger(%column)',
            ],
            'mediumText' => [
                'signature' => 'mediumText {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->mediumText(%column)',
            ],
            'morphs' => [
                'signature' => 'morphs {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->morphs(%column)',
            ],
            'multiLineString' => [
                'signature' => 'multiLineString {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->multiLineString(%column)',
            ],
            'multiPoint' => [
                'signature' => 'multiPoint {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->multiPoint(%column)',
            ],
            'multiPolygon' => [
                'signature' => 'multiPolygon {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->multiPolygon(%column)',
            ],
            'nullableMorphs' => [
                'signature' => 'nullableMorphs {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->nullableMorphs(%column)',
            ],
            'point' => [
                'signature' => 'point {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->point(%column)',
            ],
            'polygon' => [
                'signature' => 'polygon {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->polygon(%column)',
            ],
            'smallIncrements' => [
                'signature' => 'smallIncrements {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->smallIncrements(%column)',
            ],
            'smallInteger' => [
                'signature' => 'smallInteger {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->smallInteger(%column)',
            ],
            'string' => [
                'signature' => 'string {column} {length?} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->string(%column, %length)',
            ],
            'text' => [
                'signature' => 'text {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->text(%column)',
            ],
            'time' => [
                'signature' => 'time {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->time(%column)',
            ],
            'timeTz' => [
                'signature' => 'timeTz {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->timeTz(%column)',
            ],
            'timestamp' => [
                'signature' => 'timestamp {column} {--d|default=} {--u|useCurrent} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->timestamp(%column)',
            ],
            'timestampTz' => [
                'signature' => 'timestampTz {column} {--d|default=} {--u|useCurrent} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->timestampTz(%column)',
            ],
            'tinyIncrements' => [
                'signature' => 'tinyIncrements {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->tinyIncrements(%column)',
            ],
            'tinyInteger' => [
                'signature' => 'tinyInteger {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--u|unsigned} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->tinyInteger(%column)',
            ],
            'unsignedBigInteger' => [
                'signature' => 'unsignedBigInteger {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->unsignedBigInteger(%column)',
            ],
            'unsignedDecimal' => [
                'signature' => 'unsignedDecimal {column} {total=8} {places=2} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->unsignedDecimal(%column, %total, %places)',
            ],
            'unsignedInteger' => [
                'signature' => 'unsignedInteger {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->unsignedInteger(%column)',
            ],
            'unsignedMediumInteger' => [
                'signature' => 'unsignedMediumInteger {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->unsignedMediumInteger(%column)',
            ],
            'unsignedSmallInteger' => [
                'signature' => 'unsignedSmallInteger {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->unsignedSmallInteger(%column)',
            ],
            'unsignedTinyInteger' => [
                'signature' => 'unsignedTinyInteger {column} {--d|default=} {--nul|nullable} {--a|autoIncrement} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->unsignedTinyInteger(%column)',
            ],
            'uuid' => [
                'signature' => 'uuid {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->uuid(%column)',
            ],
            'year' => [
                'signature' => 'year {column} {--d|default=} {--nul|nullable} {--i|index} {--iu|unique} {--c|comment=}',
                'template' => '$table->year(%column)',
            ],
        ],
        'timestamps' => [
            'nullableTimestamps' => [
                'signature' => 'nullableTimestamps',
                'template' => '$table->timestampsTz()',
            ],
            'timestamps' => [
                'signature' => 'timestamps',
                'template' => '$table->timestamps()',
            ],
            'timestampsTz' => [
                'signature' => 'timestampsTz',
                'template' => '$table->timestampsTz()',
            ],
            'softDeletes' => [
                'signature' => 'softDeletes',
                'template' => '$table->softDeletes()',
            ],
            'softDeletesTz' => [
                'signature' => 'softDeletesTz',
                'template' => '$table->softDeletesTz()',
            ],
        ],
        'indexes' => [
            'primary' => [
                'signature' => 'primary {columns*}',
                'template' => '$table->primary(%columns)',
            ],
            'unique' => [
                'signature' => 'unique {columns*}',
                'template' => '$table->unique(%columns)',
            ],
            'index' => [
                'signature' => 'index {columns*}',
                'template' => '$table->index(%columns)',
            ],
            'spatialIndex' => [
                'signature' => 'spatialIndex {columns*}',
                'template' => '$table->spatialIndex(%columns)',
            ],
        ],
    ]
];
