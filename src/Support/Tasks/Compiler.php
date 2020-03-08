<?php


namespace Bgaze\Crud\Support\Tasks;

use BadMethodCallException;
use Bgaze\Crud\Support\Crud\Crud;
use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Definitions;

/**
 * A trait for entries templates generation.
 *
 * @author bgaze <benjamin@bgaze.fr>
 *
 * FIELDS
 *
 * @method string|array bigInteger(Entry $entry) Compile a bigInteger column
 * @method string|array binary(Entry $entry) Compile a binary column
 * @method string|array boolean(Entry $entry) Compile a boolean column
 * @method string|array char(Entry $entry) Compile a char column
 * @method string|array date(Entry $entry) Compile a date column
 * @method string|array dateTime(Entry $entry) Compile a dateTime column
 * @method string|array dateTimeTz(Entry $entry) Compile a dateTimeTz column
 * @method string|array decimal(Entry $entry) Compile a decimal column
 * @method string|array double(Entry $entry) Compile a double column
 * @method string|array enum(Entry $entry) Compile an enum column
 * @method string|array float(Entry $entry) Compile a float column
 * @method string|array geometry(Entry $entry) Compile a geometry column
 * @method string|array geometryCollection(Entry $entry) Compile a geometryCollection column
 * @method string|array integer(Entry $entry) Compile an integer column
 * @method string|array ipAddress(Entry $entry) Compile an ipAddress column
 * @method string|array json(Entry $entry) Compile a json column
 * @method string|array jsonb(Entry $entry) Compile a jsonb column
 * @method string|array lineString(Entry $entry) Compile a lineString column
 * @method string|array longText(Entry $entry) Compile a longText column
 * @method string|array macAddress(Entry $entry) Compile a macAddress column
 * @method string|array mediumInteger(Entry $entry) Compile a mediumInteger column
 * @method string|array mediumText(Entry $entry) Compile a mediumText column
 * @method string|array morphs(Entry $entry) Compile morphs columns
 * @method string|array multiLineString(Entry $entry) Compile a multiLineString column
 * @method string|array multiPoint(Entry $entry) Compile a multiPoint column
 * @method string|array multiPolygon(Entry $entry) Compile a multiPolygon column
 * @method string|array nullableMorphs(Entry $entry) Compile nullableMorphs columns
 * @method string|array nullableUuidMorphs(Entry $entry) Compile nullableUuidMorphs columns
 * @method string|array point(Entry $entry) Compile a point column
 * @method string|array polygon(Entry $entry) Compile a polygon column
 * @method string|array rememberToken(Entry $entry) Compile a rememberToken column
 * @method string|array set(Entry $entry) Compile a set column
 * @method string|array smallInteger(Entry $entry) Compile a smallInteger column
 * @method string|array softDeletes(Entry $entry) Compile a softDeletes column
 * @method string|array softDeletesTz(Entry $entry) Compile a softDeletesTz column
 * @method string|array string(Entry $entry) Compile a string column
 * @method string|array text(Entry $entry) Compile a text column
 * @method string|array time(Entry $entry) Compile a time column
 * @method string|array timeTz(Entry $entry) Compile a timeTz column
 * @method string|array timestamp(Entry $entry) Compile a timestamp column
 * @method string|array timestampTz(Entry $entry) Compile a timestampTz column
 * @method string|array timestamps(Entry $entry) Compile timestamps columns
 * @method string|array timestampsTz(Entry $entry) Compile timestampsTz columns
 * @method string|array tinyInteger(Entry $entry) Compile a tinyInteger column
 * @method string|array unsignedBigInteger(Entry $entry) Compile an unsignedBigInteger column
 * @method string|array unsignedDecimal(Entry $entry) Compile an unsignedDecimal column
 * @method string|array unsignedInteger(Entry $entry) Compile an unsignedInteger column
 * @method string|array unsignedMediumInteger(Entry $entry) Compile an unsignedMediumInteger column
 * @method string|array unsignedSmallInteger(Entry $entry) Compile an unsignedSmallInteger column
 * @method string|array unsignedTinyInteger(Entry $entry) Compile an unsignedTinyInteger column
 * @method string|array uuid(Entry $entry) Compile an uuid column
 * @method string|array uuidMorphs(Entry $entry) Compile uuidMorphs columns
 * @method string|array year(Entry $entry) Compile a year column
 *
 * INDEXES
 *
 * @method string|array index(Entry $entry) Compile an index
 * @method string|array primaryIndex(Entry $entry) Compile a primaryIndex index
 * @method string|array uniqueIndex(Entry $entry) Compile an uniqueIndex index
 * @method string|array spatialIndex(Entry $entry) Compile a spatialIndex index
 */
abstract class Compiler
{
    /**
     * @var Crud
     */
    protected $crud;


    /**
     * The class constructor
     *
     * @param  Crud  $crud  The Crud instance
     */
    public function __construct(Crud $crud)
    {
        $this->crud = $crud;
    }


    /**
     * Get the default compilation function for an entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The compiled entry
     */
    abstract public function default(Entry $entry);


    /**
     * Use default template for all existing entries if method is not defined.
     *
     * @param  string  $method  The entry method name.
     * @param  array  $parameters  The parameters passed to the method.
     *
     * @return string   The template for the entry
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (!Definitions::signatures()->has($method)) {
            throw new BadMethodCallException("Method '{$method}' does not exist.");
        }

        return call_user_func_array([$this, 'default'], $parameters);
    }


    /**
     * Run a compiler against all CRUD entries.
     *
     * @param  string  $onEmpty  A replacement value if result is empty
     * @return string
     */
    public function compile($onEmpty = '')
    {
        $content = $this->crud->getContent()
            ->map(function (Entry $entry) {
                return $this->{$entry->command()}($entry);
            })
            ->flatten()
            ->filter()
            ->implode(PHP_EOL);

        if (empty($content)) {
            return $onEmpty;
        }

        return $content;
    }



}