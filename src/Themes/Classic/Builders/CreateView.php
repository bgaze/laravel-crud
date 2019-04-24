<?php

namespace Bgaze\Crud\Themes\Classic\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Entry;
use Bgaze\Crud\Core\EntriesTemplatesTrait;

/**
 * The Create view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class CreateView extends Builder {

    use EntriesTemplatesTrait;

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('views/' . $this->crud->getPluralsKebabSlash() . "/create.blade.php");
    }

    /**
     * Build the file.
     */
    public function build() {
        $this->buildForm('views.create', 'partials.form-group');
    }

    /**
     * Build the form view file.
     * 
     * @param string $viewStub      The main stub to use to compile form view file
     * @param string $groupStub     The stub to use to compile a entry form group
     */
    public function buildForm($viewStub, $groupStub) {
        $stub = $this->stub($viewStub);

        $this->replace($stub, '#CONTENT', $this->content($groupStub));

        $this->generateFile($this->file(), $stub);
    }

    /**
     * Compile form entries.
     * 
     * @param string $groupStub The stub to use to compile a entry form group.
     * @return string
     */
    protected function content($groupStub) {
        $content = $this->crud
                ->content(false)
                ->map(function(Entry $entry) use($groupStub) {
                    return $this->formGroup($entry, $groupStub);
                })
                ->filter()
                ->implode("\n");

        if (empty($content)) {
            return '    <!-- TODO -->';
        }

        return $content;
    }

    /**
     * Compile a form group.
     * 
     * @param \Bgaze\Crud\Core\Entry $entry     The entry to compile
     * @param string $groupStub                 The stub to use to compile a entry form group.
     * @return string
     */
    protected function formGroup(Entry $entry, $groupStub) {
        $template = $this->entryTemplate($entry);

        if (empty($template)) {
            return null;
        }

        $stub = $this->stub($groupStub);

        $this
                ->replace($stub, '#FIELD', $template)
                ->replace($stub, 'ModelCamel')
                ->replace($stub, 'EntryLabel', $entry->label())
                ->replace($stub, 'EntryName', $entry->name())
        ;

        return $stub;
    }

    /**
     * Get the default template for a entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function defaultTemplate(Entry $entry) {
        return "{!! Form::text('EntryName') !!}";
    }

    /**
     * Get the template for a boolean entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function booleanTemplate(Entry $entry) {
        return "<label for=\"EntryName0\">{!! Form::radio('EntryName', 0, !\$ModelCamel->EntryName, ['id' => 'EntryName0']) !!} No</label>"
                . "\n        <label for=\"EntryName1\">{!! Form::radio('EntryName', 1, \$ModelCamel->EntryName, ['id' => 'EntryName1']) !!} Yes</label>";
    }

    /**
     * Get the template for a enum entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function enumTemplate(Entry $entry) {
        $choices = $entry->argument('allowed');

        if ($entry->option('nullable')) {
            array_unshift($choices, '');
        }

        $value = $this->compileArrayForPhp(array_combine($choices, $choices), true);

        return sprintf("{!! Form::select('EntryName', %s) !!}", $value);
    }

    /**
     * Get the template for a text entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function textTemplate(Entry $entry) {
        return "{!! Form::textarea('EntryName') !!}";
    }

    /**
     * Get the template for a rememberToken entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function rememberTokenTemplate(Entry $entry) {
        return null;
    }

    /**
     * Get the template for a softDeletes entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTemplate(Entry $entry) {
        return null;
    }

    /**
     * Get the template for a softDeletesTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTzTemplate(Entry $entry) {
        return null;
    }

    /**
     * Get the template for a timestamps entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTemplate(Entry $entry) {
        return null;
    }

    /**
     * Get the template for a timestampsTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTzTemplate(Entry $entry) {
        return null;
    }

}
