<?php

namespace Bgaze\Crud\Themes\Classic\Compilers;

use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Tasks\Compiler;
use Bgaze\Crud\Support\Utils\Helpers;
use Exception;

/**
 * Description of FormField
 *
 * @author bgaze
 */
class FormContent extends Compiler
{

    /**
     * Compile a form group.
     *
     * @param  Entry  $entry  The entry to compile
     * @param  string  $template  The stub to use to compile a entry form group.
     * @return string
     * @throws Exception
     */
    protected function formGroup(Entry $entry, $template)
    {
        return Helpers::populateStub($this->crud, 'partials.form-group', [
            '#FIELD' => $template,
            'EntryLabel' => $entry->label(),
            'EntryName' => $entry->name(),
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
        return $this->formGroup($entry, "{!! Form::text('EntryName') !!}");
    }


    /**
     * Get the form group for a boolean entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The form group for the entry
     * @throws Exception
     */
    public function boolean(Entry $entry)
    {
        $stub = "<label for=\"EntryName0\">{!! Form::radio('EntryName', 0, !\$ModelCamel->EntryName, ['id' => 'EntryName0']) !!} No</label>"
            . "\n        <label for=\"EntryName1\">{!! Form::radio('EntryName', 1, \$ModelCamel->EntryName, ['id' => 'EntryName1']) !!} Yes</label>";
        return $this->formGroup($entry, Helpers::populateString($this->crud, $stub));
    }


    /**
     * Get the form group for a enum entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The form group for the entry
     * @throws Exception
     */
    public function enum(Entry $entry)
    {
        $choices = $entry->argument('allowed');

        if ($entry->option('nullable')) {
            array_unshift($choices, '');
        }

        $value = Helpers::compileArrayForPhp(array_combine($choices, $choices), true);
        $stub = sprintf("{!! Form::select('EntryName', %s) !!}", $value);

        return $this->formGroup($entry, $stub);
    }


    /**
     * Get the form group for a text entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The form group for the entry
     * @throws Exception
     */
    public function text(Entry $entry)
    {
        return $this->formGroup($entry, "{!! Form::textarea('EntryName') !!}");
    }


    /**
     * Get the form group for a rememberToken entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The form group for the entry
     */
    public function rememberToken(Entry $entry)
    {
        return null;
    }


    /**
     * Get the form group for a softDeletes entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The form group for the entry
     */
    public function softDeletes(Entry $entry)
    {
        return null;
    }


    /**
     * Get the form group for a softDeletesTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The form group for the entry
     */
    public function softDeletesTz(Entry $entry)
    {
        return null;
    }


    /**
     * Get the form group for a timestamps entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The form group for the entry
     */
    public function timestamps(Entry $entry)
    {
        return null;
    }


    /**
     * Get the form group for a timestampsTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string The form group for the entry
     */
    public function timestampsTz(Entry $entry)
    {
        return null;
    }

}
