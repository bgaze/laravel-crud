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
     * @param  string|array  $template  The stub(s) to use to compile a entry form group.
     * @return string
     * @throws Exception
     */
    protected function formGroup(Entry $entry, $template)
    {

        if (is_array($template)) {
            $template = implode("\n", $template);
        }

        $template = Helpers::populateString($this->crud, $template, [
            'EntryLabel' => $entry->label(),
            'EntryName' => $entry->name(),
        ]);

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
     * @return string|array The compiled entry
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
     * @return string|array The form group for the entry
     * @throws Exception
     */
    public function boolean(Entry $entry)
    {
        return $this->formGroup($entry, [
            "<label for=\"EntryName0\">{!! Form::radio('EntryName', 0, !\$ModelCamel->EntryName, ['id' => 'EntryName0']) !!} No</label>",
            "<label for=\"EntryName1\">{!! Form::radio('EntryName', 1, \$ModelCamel->EntryName, ['id' => 'EntryName1']) !!} Yes</label>",
        ]);
    }


    /**
     * Get the form group for a enum entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     * @throws Exception
     */
    public function enum(Entry $entry)
    {
        $choices = $entry->argument('allowed');

        if ($entry->option('nullable')) {
            array_unshift($choices, '');
        }

        $choices = Helpers::compileArrayForPhp(array_combine($choices, $choices), true);

        return $this->formGroup($entry, sprintf("{!! Form::select('EntryName', %s) !!}", $choices));
    }


    /**
     * Get the form group for a longText entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     * @throws Exception
     */
    public function longText(Entry $entry)
    {
        return $this->text($entry);
    }


    /**
     * Get the form group for a mediumText entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     * @throws Exception
     */
    public function mediumText(Entry $entry)
    {
        return $this->text($entry);
    }


    /**
     * Get the form group for a morphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     */
    public function morphs(Entry $entry)
    {
        return null;
    }


    /**
     * Get the form group for a nullableMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     */
    public function nullableMorphs(Entry $entry)
    {
        return $this->morphs($entry);
    }


    /**
     * Get the form group for a nullableUuidMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     */
    public function nullableUuidMorphs(Entry $entry)
    {
        return $this->morphs($entry);
    }


    /**
     * Get the form group for a rememberToken entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     */
    public function rememberToken(Entry $entry)
    {
        return null;
    }


    /**
     * Get the form group for a set entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     * @throws Exception
     */
    public function set(Entry $entry)
    {
        $choices = array_combine($entry->argument('allowed'), $entry->argument('allowed'));

        $choices = Helpers::compileArrayForPhp($choices, true);

        $stub = sprintf("{!! Form::select('EntryName[]', %s, null, ['multiple' => true]) !!}", $choices);

        return $this->formGroup($entry, $stub);
    }


    /**
     * Get the form group for a softDeletes entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     */
    public function softDeletes(Entry $entry)
    {
        return null;
    }


    /**
     * Get the form group for a softDeletesTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     */
    public function softDeletesTz(Entry $entry)
    {
        return $this->softDeletes($entry);
    }


    /**
     * Get the form group for a text entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     * @throws Exception
     */
    public function text(Entry $entry)
    {
        return $this->formGroup($entry, "{!! Form::textarea('EntryName') !!}");
    }


    /**
     * Get the form group for a timestamps entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     */
    public function timestamps(Entry $entry)
    {
        return null;
    }


    /**
     * Get the form group for a timestampsTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     */
    public function timestampsTz(Entry $entry)
    {
        return $this->timestamps($entry);
    }


    /**
     * Get the form group for a uuidMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The form group for the entry
     */
    public function uuidMorphs(Entry $entry)
    {
        return $this->morphs($entry);
    }

}
