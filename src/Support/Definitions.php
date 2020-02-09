<?php

namespace Bgaze\Crud\Support;

use Exception;
use Illuminate\Support\Collection;

/**
 * Definitions class for all const used in package.
 *
 * @author bgaze
 */
class Definitions
{

    /**
     * Regular expression used to validate table columns name.
     */
    const COLUMN_FORMAT = '/^[a-z]([a-z0-9_]*[a-z0-9])?$/';

    /**
     * Regular expression used to validate model FullName.
     */
    const MODEL_NAME_FORMAT = '/^((([A-Z][a-z]+)+)(\\\\|\/))*(([A-Z][a-z]+)+)$/';

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
        'firstKey' => 'nullable|table_column',
        'foreignKey' => 'nullable|table_column',
        'foreignPivotKey' => 'nullable|table_column',
        'id' => 'nullable|table_column',
        'index' => 'boolean',
        'inverse' => 'boolean',
        'length' => 'nullable|integer',
        'localKey' => 'nullable|table_column',
        'name' => 'table_column',
        'nullable' => 'boolean',
        'ownerKey' => 'nullable|table_column',
        'parentKey' => 'nullable|table_column',
        'plurals' => 'nullable|model_name',
        'places' => 'integer',
        'related' => 'model_name',
        'relatedKey' => 'nullable|table_column',
        'relatedPivotKey' => 'nullable|table_column',
        'secondKey' => 'nullable|table_column',
        'secondLocalKey' => 'nullable|table_column',
        'table' => 'nullable|table_column',
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
     * The available modifiers available for column entries.
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
     * The available indexes signatures.
     */
    const INDEXES = [
        'index' => '{columns*}',
        'primaryIndex' => '{columns*}',
        'uniqueIndex' => '{columns*}',
        'spatialIndex' => '{columns*}',
    ];

    /*
     * The available columns signatures.
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
     * @return Collection
     */
    public static function signatures()
    {
        return collect(static::COLUMNS)->merge(static::INDEXES);
    }


    /**
     * Get a signature by entry name.
     *
     * @param  string  $name  The entry name
     * @param  bool  $parsed  Pass true to get entry name, arguments and options as separated strings.
     * @return string|array   The entry signature
     * @throws Exception
     */
    public static function signature($name, $parsed = false)
    {
        $signature = static::COLUMNS[$name] ?? static::INDEXES[$name] ?? false;

        if ($signature === false) {
            throw new Exception("Unknown entry type: {$name}");
        }

        if ($parsed) {
            preg_match('/^((\s?\{[^-\}]+\})*)/', $signature, $a);
            preg_match('/((\{--[^\}]+\}\s?)*)$/', $signature, $o);
            return ['name' => $name, 'arguments' => $a[1], 'options' => $o[1]];
        }

        return $signature;
    }


    /**
     * Get models directory, based on global configuration.
     *
     * @return string
     */
    public static function modelsDirectory()
    {
        $dir = config('crud.models-directory', false);

        if ($dir === true) {
            return 'Models';
        }

        if ($dir && !empty($dir)) {
            return $dir;
        }

        return false;
    }


    /**
     * Get models namespace, based on global configuration.
     *
     * @return string
     */
    public static function modelsNamespace()
    {
        $appNamespace = trim(app()->getNamespace(), '\\');

        if (static::modelsDirectory()) {
            return $appNamespace . '\\' . static::modelsDirectory();
        }

        return $appNamespace;
    }

}
