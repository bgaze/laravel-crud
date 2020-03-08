<?php

namespace Bgaze\Crud\Themes\Classic\Compilers;

use Bgaze\Crud\Support\Crud\Crud;
use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Tasks\Compiler;
use Bgaze\Crud\Support\Utils\Helpers;
use Exception;

/**
 * Description of IndexTbody
 *
 * @author bgaze
 */
class PrintContent extends Compiler
{

    /**
     * The stub used to generate rows
     *
     * @var string
     */
    protected $stub;


    /**
     * The class constructor
     *
     * @param  Crud  $crud  The Crud instance
     * @param  string  $stub  The stub used to generate rows
     */
    public function __construct(Crud $crud, $stub)
    {
        parent::__construct($crud);
        $this->stub = $stub;
    }


    /**
     * Generate a table cell
     *
     * @param  string  $label
     * @param  string  $name
     * @param  string  $template
     * @return string
     * @throws Exception
     */
    protected function printGroup($label, $name, $template = '$ModelCamel->EntryName')
    {
        $template = Helpers::populateString($this->crud, $template, [
            'EntryLabel' => $label,
            'EntryName' => $name,
        ]);

        return Helpers::populateStub($this->crud, $this->stub, [
            '$VALUE' => $template,
            'EntryLabel' => $label,
            'EntryName' => $name,
        ]);
    }


    /**
     * Get the default compilation function for an entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The compiled entry
     * @throws Exception
     */
    public function default(Entry $entry)
    {
        if ($entry->isIndex()) {
            return null;
        }

        return $this->printGroup($entry->label(), $entry->name());
    }


    /**
     * Compile a date entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     * @throws Exception
     */
    public function date(Entry $entry)
    {
        return $this->printGroup($entry->label(), $entry->name(), "\$ModelCamel->EntryName->format('Y-m-d')");
    }


    /**
     * Compile a dateTime entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     * @throws Exception
     */
    public function dateTime(Entry $entry)
    {
        return $this->printGroup($entry->label(), $entry->name(), "\$ModelCamel->EntryName->format('Y-m-d H:i:s')");
    }


    /**
     * Compile a dateTimeTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     * @throws Exception
     */
    public function dateTimeTz(Entry $entry)
    {
        return $this->dateTime($entry);
    }


    /**
     * Compile a morphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     */
    public function morphs(Entry $entry)
    {
        return null;
    }


    /**
     * Compile a nullableMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     */
    public function nullableMorphs(Entry $entry)
    {
        return null;
    }


    /**
     * Compile a nullableUuidMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     */
    public function nullableUuidMorphs(Entry $entry)
    {
        return null;
    }


    /**
     * Compile a rememberToken entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     */
    public function rememberToken(Entry $entry)
    {
        return null;
    }


    /**
     * Compile a set entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     * @throws Exception
     */
    public function set(Entry $entry)
    {
        return $this->printGroup($entry->label(), $entry->name(), "implode(', ', \$ModelCamel->EntryName)");
    }


    /**
     * Compile a softDeletes entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     */
    public function softDeletes(Entry $entry)
    {
        return null;
    }


    /**
     * Compile a softDeletesTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     */
    public function softDeletesTz(Entry $entry)
    {
        return $this->softDeletes($entry);
    }


    /**
     * Compile a timestamp entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     * @throws Exception
     */
    public function timestamp(Entry $entry)
    {
        return $this->dateTime($entry);
    }


    /**
     * Compile a timestampTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     * @throws Exception
     */
    public function timestampTz(Entry $entry)
    {
        return $this->dateTime($entry);
    }


    /**
     * Compile a timestamps entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     * @throws Exception
     */
    public function timestamps(Entry $entry)
    {
        return $this->printGroup('Created at', 'created_at') . "\n" . $this->printGroup('Updated at', 'updated_at');
    }


    /**
     * Compile a timestampsTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     * @throws Exception
     */
    public function timestampsTz(Entry $entry)
    {
        return $this->timestamps($entry);
    }


    /**
     * Compile a uuidMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The template for the entry
     */
    public function uuidMorphs(Entry $entry)
    {
        return null;
    }

}
