<?php

namespace Bgaze\Crud\Themes\Classic\Compilers;

use Bgaze\Crud\Core\Compiler;
use Bgaze\Crud\Core\Entry;

/**
 * Description of FormField
 *
 * @author bgaze
 */
class FormContent extends Compiler {

    /**
     * Compile a form group.
     * 
     * @param \Bgaze\Crud\Core\Entry $entry     The entry to compile
     * @param string $template                  The stub to use to compile a entry form group.
     * @return string
     */
    protected function formGroup(Entry $entry, $template) {
        $stub = $this->stub('partials.form-group');

        $this
                ->replace($stub, '#FIELD', $template)
                ->replace($stub, 'ModelCamel')
                ->replace($stub, 'EntryLabel', $entry->label())
                ->replace($stub, 'EntryName', $entry->name())
        ;

        return $stub;
    }

    /**
     * Get the default compilation function for an entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The compiled entry
     */
    public function compileDefault(Entry $entry) {
        return $this->formGroup($entry, "{!! Form::text('EntryName') !!}");
    }

    /**
     * Get the form group for a boolean entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The form group for the entry
     */
    public function boolean(Entry $entry) {
        $stub = "<label for=\"EntryName0\">{!! Form::radio('EntryName', 0, !\$ModelCamel->EntryName, ['id' => 'EntryName0']) !!} No</label>"
                . "\n        <label for=\"EntryName1\">{!! Form::radio('EntryName', 1, \$ModelCamel->EntryName, ['id' => 'EntryName1']) !!} Yes</label>";
        return $this->formGroup($entry, $stub);
    }

    /**
     * Get the form group for a enum entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The form group for the entry
     */
    public function enum(Entry $entry) {
        $choices = $entry->argument('allowed');

        if ($entry->option('nullable')) {
            array_unshift($choices, '');
        }

        $value = $this->compileArrayForPhp(array_combine($choices, $choices), true);
        $stub = sprintf("{!! Form::select('EntryName', %s) !!}", $value);

        return $this->formGroup($entry, $stub);
    }

    /**
     * Get the form group for a text entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The form group for the entry
     */
    public function text(Entry $entry) {
        return $this->formGroup($entry, "{!! Form::textarea('EntryName') !!}");
    }

    /**
     * Get the form group for a rememberToken entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The form group for the entry
     */
    public function rememberToken(Entry $entry) {
        return null;
    }

    /**
     * Get the form group for a softDeletes entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The form group for the entry
     */
    public function softDeletes(Entry $entry) {
        return null;
    }

    /**
     * Get the form group for a softDeletesTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The form group for the entry
     */
    public function softDeletesTz(Entry $entry) {
        return null;
    }

    /**
     * Get the form group for a timestamps entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The form group for the entry
     */
    public function timestamps(Entry $entry) {
        return null;
    }

    /**
     * Get the form group for a timestampsTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The form group for the entry
     */
    public function timestampsTz(Entry $entry) {
        return null;
    }

}
