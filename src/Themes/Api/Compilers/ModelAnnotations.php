<?php


namespace Bgaze\Crud\Themes\Api\Compilers;


use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Tasks\Compiler;
use Exception;

class ModelAnnotations extends Compiler
{

    /**
     * Generate a phpDocumentor property annotation
     *
     * @param  string  $type  The property type
     * @param  string  $name  The property name
     * @return string
     */
    protected function property($type, $name)
    {
        return "* @property  {$type}  \${$name}";
    }


    /**
     * Generate a PhpDocumentor annotation for an entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function default(Entry $entry)
    {
        return $this->property('string', $entry->name());
    }


    /**
     * Run a compiler against all CRUD entries.
     *
     * @param  string  $onEmpty  A replacement value if result is empty
     * @return string|array
     */
    public function compile($onEmpty = '')
    {
        $entries = parent::compile($onEmpty);

        if (empty($entries)) {
            return "/**\n* {$this->crud->ModelClass}\n*/";
        }

        return "/**\n* {$this->crud->ModelClass}\n* \n{$entries}\n*/";
    }


    /**
     * Generate a PhpDocumentor annotation for a bigInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function bigInteger(Entry $entry)
    {
        return $this->integer($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a boolean entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function boolean(Entry $entry)
    {
        return $this->property('boolean', $entry->name());
    }


    /**
     * Generate a PhpDocumentor annotation for a date entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function date(Entry $entry)
    {
        return $this->property('Carbon', $entry->name());
    }


    /**
     * Generate a PhpDocumentor annotation for a dateTime entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function dateTime(Entry $entry)
    {
        return $this->date($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a dateTimeTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function dateTimeTz(Entry $entry)
    {
        return $this->date($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a decimal entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function decimal(Entry $entry)
    {
        return $this->float($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a double entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function double(Entry $entry)
    {
        return $this->float($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a float entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function float(Entry $entry)
    {
        return $this->property('float', $entry->name());
    }


    /**
     * Generate a PhpDocumentor annotation for a integer entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function integer(Entry $entry)
    {
        return $this->property('integer', $entry->name());
    }


    /**
     * Generate a PhpDocumentor annotation for a json entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function json(Entry $entry)
    {
        return $this->property('array', $entry->name());
    }


    /**
     * Generate a PhpDocumentor annotation for a jsonb entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function jsonb(Entry $entry)
    {
        return $this->json($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a mediumInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function mediumInteger(Entry $entry)
    {
        return $this->integer($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a morphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function morphs(Entry $entry)
    {
        return [
            $this->property('integer', $entry->argument('name') . '_id'),
            $this->property('string', $entry->argument('name') . '_type'),
        ];
    }


    /**
     * Generate a PhpDocumentor annotation for a nullableMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function nullableMorphs(Entry $entry)
    {
        return $this->morphs($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a nullableUuidMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function nullableUuidMorphs(Entry $entry)
    {
        return $this->uuidMorphs($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a set entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function set(Entry $entry)
    {
        return $this->property('array', $entry->name());
    }


    /**
     * Generate a PhpDocumentor annotation for a smallInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function smallInteger(Entry $entry)
    {
        return $this->integer($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a softDeletes entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function softDeletes(Entry $entry)
    {
        return $this->property('Carbon', 'deleted_at');
    }


    /**
     * Generate a PhpDocumentor annotation for a softDeletesTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function softDeletesTz(Entry $entry)
    {
        return $this->softDeletes($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a time entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function time(Entry $entry)
    {
        return $this->date($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a timeTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timeTz(Entry $entry)
    {
        return $this->date($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a timestamp entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestamp(Entry $entry)
    {
        return $this->date($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a timestampTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestampTz(Entry $entry)
    {
        return $this->date($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a timestamps entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestamps(Entry $entry)
    {
        return [
            $this->property('Carbon', 'created_at'),
            $this->property('Carbon', 'updated_at'),
        ];
    }


    /**
     * Generate a PhpDocumentor annotation for a timestampsTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestampsTz(Entry $entry)
    {
        return $this->timestamps($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a tinyInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function tinyInteger(Entry $entry)
    {
        return $this->integer($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a unsignedBigInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedBigInteger(Entry $entry)
    {
        return $this->integer($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a unsignedDecimal entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedDecimal(Entry $entry)
    {
        return $this->float($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a unsignedInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedInteger(Entry $entry)
    {
        return $this->integer($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a unsignedMediumInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedMediumInteger(Entry $entry)
    {
        return $this->integer($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a unsignedSmallInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedSmallInteger(Entry $entry)
    {
        return $this->integer($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a unsignedTinyInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedTinyInteger(Entry $entry)
    {
        return $this->integer($entry);
    }


    /**
     * Generate a PhpDocumentor annotation for a uuidMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function uuidMorphs(Entry $entry)
    {
        return [
            $this->property('string', $entry->argument('name') . '_id'),
            $this->property('string', $entry->argument('name') . '_type'),
        ];
    }


    /**
     * Generate a PhpDocumentor annotation for a year entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function year(Entry $entry)
    {
        return $this->integer($entry);
    }

}
