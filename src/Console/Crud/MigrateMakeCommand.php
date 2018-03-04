<?php

namespace Bgaze\Crud\Console\Crud;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as Base;

class MigrateMakeCommand extends Base {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'bgaze:crud:migration {name : The name of the migration.}
        {--create= : The table to be created.}
        {--table= : The table to migrate.}
        {--path= : The location where the migration file should be created.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD migration file';

    protected function specialColumn() {
        return [
            'increments' => [
                'template' => '$table->increments(\'%1$s\')'
            ],
            'morphs' => [
                'template' => '$table->morphs(\'%1$s\')'
            ],
            'nullableMorphs' => [
                'template' => '$table->nullableMorphs(\'%1$s\')'
            ],
            'rememberToken' => [
                'template' => '$table->rememberToken()'
            ],
            'softDeletes' => [
                'template' => '$table->softDeletes()'
            ],
            'softDeletesTz' => [
                'template' => '$table->softDeletesTz()'
            ],
            'nullableTimestamps' => [
                'template' => '$table->nullableTimestamps()'
            ],
            'timestampsTz' => [
                'template' => '$table->timestampsTz()'
            ]
        ];
    }

    protected function columns() {
        return [
            'bigIncrements' => [
                'template' => '$table->bigIncrements(\'%1$s\')'
            ],
            'bigInteger' => [
                'template' => '$table->bigInteger(\'%1$s\')'
            ],
            'binary' => [
                'template' => '$table->binary(\'%1$s\')'
            ],
            'boolean' => [
                'template' => '$table->boolean(\'%1$s\')'
            ],
            'char' => [
                'template' => '$table->char(\'%1$s\', %2$d)'
            ],
            'date' => [
                'template' => '$table->date(\'%1$s\')'
            ],
            'dateTime' => [
                'template' => '$table->dateTime(\'%1$s\')'
            ],
            'dateTimeTz' => [
                'template' => '$table->dateTimeTz(\'%1$s\')'
            ],
            'decimal' => [
                'template' => '$table->decimal(\'%1$s\', %2$d, %3$d)'
            ],
            'double' => [
                'template' => '$table->double(\'%1$s\', %2$d, %3$d)'
            ],
            'enum' => [
                'template' => '$table->enum(\'%1$s\', [%2$s])'
            ],
            'float' => [
                'template' => '$table->float(\'%1$s\', %2$d, %3$d)'
            ],
            'geometry' => [
                'template' => '$table->geometry(\'%1$s\')'
            ],
            'geometryCollection' => [
                'template' => '$table->geometryCollection(\'%1$s\')'
            ],
            'integer' => [
                'template' => '$table->integer(\'%1$s\')'
            ],
            'ipAddress' => [
                'template' => '$table->ipAddress(\'%1$s\')'
            ],
            'json' => [
                'template' => '$table->json(\'%1$s\')'
            ],
            'jsonb' => [
                'template' => '$table->jsonb(\'%1$s\')'
            ],
            'lineString' => [
                'template' => '$table->lineString(\'%1$s\')'
            ],
            'longText' => [
                'template' => '$table->longText(\'%1$s\')'
            ],
            'macAddress' => [
                'template' => '$table->macAddress(\'%1$s\')'
            ],
            'mediumInteger' => [
                'template' => '$table->mediumInteger(\'%1$s\')'
            ],
            'mediumText' => [
                'template' => '$table->mediumText(\'%1$s\')'
            ],
            'multiLineString' => [
                'template' => '$table->multiLineString(\'%1$s\')'
            ],
            'multiPoint' => [
                'template' => '$table->multiPoint(\'%1$s\')'
            ],
            'multiPolygon' => [
                'template' => '$table->multiPolygon(\'%1$s\')'
            ],
            'point' => [
                'template' => '$table->point(\'%1$s\')'
            ],
            'polygon' => [
                'template' => '$table->polygon(\'%1$s\')'
            ],
            'smallInteger' => [
                'template' => '$table->smallInteger(\'%1$s\')'
            ],
            'string' => [
                'template' => '$table->string(\'%1$s\', %2$d)'
            ],
            'text' => [
                'template' => '$table->text(\'%1$s\')'
            ],
            'time' => [
                'template' => '$table->time(\'%1$s\')'
            ],
            'timeTz' => [
                'template' => '$table->timeTz(\'%1$s\')'
            ],
            'timestamp' => [
                'template' => '$table->timestamp(\'%1$s\')'
            ],
            'timestampTz' => [
                'template' => '$table->timestampTz(\'%1$s\')'
            ],
            'timestamps' => [
                'template' => '$table->timestamps()'
            ],
            'tinyInteger' => [
                'template' => '$table->tinyInteger(\'%1$s\')'
            ],
            'uuid' => [
                'template' => '$table->uuid(\'%1$s\')'
            ],
            'year' => [
                'template' => '$table->year(\'%1$s\')'
            ]
        ];
    }

    protected function modifiers() {
        return [
            'autoIncrement' => '->autoIncrement()',
            'comment' => '->comment(\'my comment\')',
            'default' => '->default($value)',
            'nullable' => '->nullable()',
            'unsigned' => '->unsigned()',
            'unique' => '->unique()',
            'useCurrent' => '->useCurrent()'
        ];
    }

}
