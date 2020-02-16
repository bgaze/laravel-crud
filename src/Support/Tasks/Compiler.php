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
 * @method string bigInteger(Entry $entry) Compile a bigInteger column
 * @method string binary(Entry $entry) Compile a binary column
 * @method string boolean(Entry $entry) Compile a boolean column
 * @method string char(Entry $entry) Compile a char column
 * @method string date(Entry $entry) Compile a date column
 * @method string dateTime(Entry $entry) Compile a dateTime column
 * @method string dateTimeTz(Entry $entry) Compile a dateTimeTz column
 * @method string decimal(Entry $entry) Compile a decimal column
 * @method string double(Entry $entry) Compile a double column
 * @method string enum(Entry $entry) Compile an enum column
 * @method string float(Entry $entry) Compile a float column
 * @method string geometry(Entry $entry) Compile a geometry column
 * @method string geometryCollection(Entry $entry) Compile a geometryCollection column
 * @method string integer(Entry $entry) Compile an integer column
 * @method string ipAddress(Entry $entry) Compile an ipAddress column
 * @method string json(Entry $entry) Compile a json column
 * @method string jsonb(Entry $entry) Compile a jsonb column
 * @method string lineString(Entry $entry) Compile a lineString column
 * @method string longText(Entry $entry) Compile a longText column
 * @method string macAddress(Entry $entry) Compile a macAddress column
 * @method string mediumInteger(Entry $entry) Compile a mediumInteger column
 * @method string mediumText(Entry $entry) Compile a mediumText column
 * @method string morphs(Entry $entry) Compile a morphs column
 * @method string multiLineString(Entry $entry) Compile a multiLineString column
 * @method string multiPoint(Entry $entry) Compile a multiPoint column
 * @method string multiPolygon(Entry $entry) Compile a multiPolygon column
 * @method string nullableMorphs(Entry $entry) Compile a nullableMorphs column
 * @method string point(Entry $entry) Compile a point column
 * @method string polygon(Entry $entry) Compile a polygon column
 * @method string rememberToken(Entry $entry) Compile a rememberToken column
 * @method string smallInteger(Entry $entry) Compile a smallInteger column
 * @method string softDeletes(Entry $entry) Compile a softDeletes column
 * @method string softDeletesTz(Entry $entry) Compile a softDeletesTz column
 * @method string string(Entry $entry) Compile a string column
 * @method string text(Entry $entry) Compile a text column
 * @method string time(Entry $entry) Compile a time column
 * @method string timeTz(Entry $entry) Compile a timeTz column
 * @method string timestamp(Entry $entry) Compile a timestamp column
 * @method string timestampTz(Entry $entry) Compile a timestampTz column
 * @method string timestamps(Entry $entry) Compile a timestamps column
 * @method string timestampsTz(Entry $entry) Compile a timestampsTz column
 * @method string tinyInteger(Entry $entry) Compile a tinyInteger column
 * @method string unsignedBigInteger(Entry $entry) Compile an unsignedBigInteger column
 * @method string unsignedDecimal(Entry $entry) Compile an unsignedDecimal column
 * @method string unsignedInteger(Entry $entry) Compile an unsignedInteger column
 * @method string unsignedMediumInteger(Entry $entry) Compile an unsignedMediumInteger column
 * @method string unsignedSmallInteger(Entry $entry) Compile an unsignedSmallInteger column
 * @method string unsignedTinyInteger(Entry $entry) Compile an unsignedTinyInteger column
 * @method string uuid(Entry $entry) Compile an uuid column
 * @method string year(Entry $entry) Compile a year column
 *
 * INDEXES
 *
 * @method string index(Entry $entry) Compile an index
 * @method string primaryIndex(Entry $entry) Compile a primaryIndex index
 * @method string uniqueIndex(Entry $entry) Compile an uniqueIndex index
 * @method string spatialIndex(Entry $entry) Compile a spatialIndex index
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