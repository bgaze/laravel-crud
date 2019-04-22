<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Entry;
use Bgaze\Crud\Core\EntriesTemplatesTrait;

/**
 * Description of Model
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class ModelClass extends Builder {

    use EntriesTemplatesTrait;

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return app_path(trim($this->crud->modelsSubDirectory() . '/' . $this->crud->model()->implode('/') . '.php', '/'));
    }

    /**
     * Build the file.
     */
    public function build() {
        $stub = $this->stub('model');

        $this
                ->replace($stub, '#TIMESTAMPS', $this->timestamps())
                ->replace($stub, '#SOFTDELETE', $this->softDeletes())
                ->replace($stub, '#FILLABLES', $this->fillables())
                ->replace($stub, '#DATES', $this->dates())
                ->replace($stub, '#PROPERTIES', $this->properties())
        ;

        $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * Compile CRUD timestamps.
     * 
     * @return string
     */
    protected function timestamps() {
        return $this->crud->timestamps() ? 'public $timestamps = true;' : '';
    }

    /**
     * Compile CRUD soft deletes.
     * 
     * @return string
     */
    protected function softDeletes() {
        return $this->crud->softDeletes() ? 'use \Illuminate\Database\Eloquent\SoftDeletes;' : '';
    }

    /**
     * Compile CRUD content to Model fillables array.
     * 
     * @return string
     */
    protected function fillables() {
        $fillables = $this->crud
                ->content(false)
                ->map(function(Entry $entry) {
                    if (in_array($entry->name(), ['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz'])) {
                        return false;
                    }
                    return $entry->columns();
                })
                ->flatten()
                ->filter()
                ->toArray();
        return 'protected $fillable = ' . $this->compileArrayForPhp($fillables) . ';';
    }

    /**
     * Compile CRUD content to Model dates array.
     * 
     * @return string
     */
    protected function dates() {
        $dates = $this->crud
                ->content(false)
                ->filter(function(Entry $entry) {
                    return $entry->isDate();
                })
                ->keys();

        if ($this->crud->softDeletes() && !$dates->contains('deleted_at')) {
            $dates->prepend('deleted_at');
        }

        return 'protected $dates = ' . $this->compileArrayForPhp($dates->toArray()) . ';';
    }

    /**
     * Compile CRUD content to phpDocumentor properties annotations.
     * 
     * @return string
     */
    protected function properties() {
        $content = $this->crud->content(false)->map(function(Entry $entry) {
            return $this->entryTemplate($entry);
        });

        return "/**\n" . $content->implode("\n") . "\n */";
    }

    /**
     * Generate a phpDocumentor property annotation
     * 
     * @param string $type  The property type
     * @param string $name  The property name
     * @return string
     */
    protected function property($type, $name) {
        return "* @property {$type} \${$name}";
    }

    /**
     * Get the default template for a entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function defaultTemplate(Entry $entry) {
        return $this->property('string', $entry->name());
    }

    /**
     * Get the template for a bigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function bigIntegerTemplate(Entry $entry) {
        return $this->integerTemplate($entry);
    }

    /**
     * Get the template for a boolean entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function booleanTemplate(Entry $entry) {
        return $this->property('boolean', $entry->name());
    }

    /**
     * Get the template for a date entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTemplate(Entry $entry) {
        return $this->property('\Carbon\Carbon', $entry->name());
    }

    /**
     * Get the template for a dateTime entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTimeTemplate(Entry $entry) {
        return $this->dateTemplate($entry);
    }

    /**
     * Get the template for a dateTimeTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTimeTzTemplate(Entry $entry) {
        return $this->dateTemplate($entry);
    }

    /**
     * Get the template for a decimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function decimalTemplate(Entry $entry) {
        return $this->floatTemplate($entry);
    }

    /**
     * Get the template for a double entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function doubleTemplate(Entry $entry) {
        return $this->floatTemplate($entry);
    }

    /**
     * Get the template for a float entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function floatTemplate(Entry $entry) {
        return $this->property('float', $entry->name());
    }

    /**
     * Get the template for a integer entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function integerTemplate(Entry $entry) {
        return $this->property('integer', $entry->name());
    }

    /**
     * Get the template for a json entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function jsonTemplate(Entry $entry) {
        return $this->property('array', $entry->name());
    }

    /**
     * Get the template for a jsonb entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function jsonbTemplate(Entry $entry) {
        return $this->jsonTemplate($entry);
    }

    /**
     * Get the template for a mediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function mediumIntegerTemplate(Entry $entry) {
        return $this->integerTemplate($entry);
    }

    /**
     * Get the template for a morphs entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function morphsTemplate(Entry $entry) {
        return $this->property('integer', $entry->name() . '_id') . "\n" . $this->property('string', $entry->name() . '_type');
    }

    /**
     * Get the template for a nullableMorphs entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function nullableMorphsTemplate(Entry $entry) {
        return $this->morphsTemplate($entry);
    }

    /**
     * Get the template for a smallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function smallIntegerTemplate(Entry $entry) {
        return $this->integerTemplate($entry);
    }

    /**
     * Get the template for a softDeletes entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTemplate(Entry $entry) {
        return $this->property('\Carbon\Carbon', 'deleted_at');
    }

    /**
     * Get the template for a softDeletesTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTzTemplate(Entry $entry) {
        return $this->softDeletesTemplate($entry);
    }

    /**
     * Get the template for a time entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timeTemplate(Entry $entry) {
        return $this->dateTemplate($entry);
    }

    /**
     * Get the template for a timeTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timeTzTemplate(Entry $entry) {
        return $this->dateTemplate($entry);
    }

    /**
     * Get the template for a timestamp entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampTemplate(Entry $entry) {
        return $this->dateTemplate($entry);
    }

    /**
     * Get the template for a timestampTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampTzTemplate(Entry $entry) {
        return $this->dateTemplate($entry);
    }

    /**
     * Get the template for a timestamps entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTemplate(Entry $entry) {
        return $this->property('\Carbon\Carbon', 'created_at') . "\n" . $this->property('\Carbon\Carbon', 'updated_at');
    }

    /**
     * Get the template for a timestampsTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTzTemplate(Entry $entry) {
        return $this->timestampsTemplate($entry);
    }

    /**
     * Get the template for a tinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function tinyIntegerTemplate(Entry $entry) {
        return $this->integerTemplate($entry);
    }

    /**
     * Get the template for a unsignedBigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedBigIntegerTemplate(Entry $entry) {
        return $this->integerTemplate($entry);
    }

    /**
     * Get the template for a unsignedDecimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedDecimalTemplate(Entry $entry) {
        return $this->floatTemplate($entry);
    }

    /**
     * Get the template for a unsignedInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedIntegerTemplate(Entry $entry) {
        return $this->integerTemplate($entry);
    }

    /**
     * Get the template for a unsignedMediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedMediumIntegerTemplate(Entry $entry) {
        return $this->integerTemplate($entry);
    }

    /**
     * Get the template for a unsignedSmallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedSmallIntegerTemplate(Entry $entry) {
        return $this->integerTemplate($entry);
    }

    /**
     * Get the template for a unsignedTinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedTinyIntegerTemplate(Entry $entry) {
        return $this->integerTemplate($entry);
    }

    /**
     * Get the template for a year entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function yearTemplate(Entry $entry) {
        return $this->dateTemplate($entry);
    }

}
