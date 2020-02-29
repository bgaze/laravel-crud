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
     * @return string
     * @throws Exception
     */
    protected function printGroup($label, $name)
    {
        return Helpers::populateStub($this->crud, $this->stub, [
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

}
