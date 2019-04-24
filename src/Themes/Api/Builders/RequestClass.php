<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Entry;
use Bgaze\Crud\Core\EntriesTemplatesTrait;

/**
 * The Request class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class RequestClass extends Builder {

    use EntriesTemplatesTrait;

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return app_path('Http/Requests/' . $this->crud->model()->implode('/') . 'FormRequest.php');
    }

    /**
     * Build the file.
     */
    public function build() {
        $stub = $this->stub('request');

        $this->replace($stub, '#CONTENT', $this->content());

        $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * Compile the file content.
     * 
     * @return string
     */
    protected function content() {
        $content = $this->crud
                ->content(false)
                ->map(function(Entry $entry) {
                    $template = $this->entryTemplate($entry);

                    if ($template === false) {
                        return false;
                    }

                    return $this->requestGroup($entry, $template);
                })
                ->filter()
                ->implode("\n");

        if (empty($content)) {
            return '// TODO';
        }

        return $content;
    }

    /**
     * Compile content to request class body line.
     * 
     * @param \Bgaze\Crud\Core\Entry $entry     The entry
     * @param string $template                  The entry rules
     * @return string
     */
    protected function requestGroup(Entry $entry, $template) {
        $rules = [];
        $definition = $entry->definition();

        if ($definition->hasOption('nullable')) {
            $rules[] = $entry->option('nullable') ? 'nullable' : 'required';
        } elseif (preg_match('/^nullable/', $entry->command())) {
            $rules[] = 'nullable';
        } else {
            $rules[] = 'required';
        }

        $rules[] = $template;

        if (in_array('unique', $definition->getOptions()) && $definition->getOption('unique')) {
            $rules[] = 'unique:' . $this->crud->getTableName() . ',' . $entry->name();
        }

        return sprintf("'%s' => '%s',", $entry->name(), implode('|', array_filter($rules)));
    }

    /**
     * Get the default rules for a entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules of the entry
     */
    public function defaultTemplate(Entry $entry) {
        return null;
    }

    /**
     * Get the rules for a bigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function bigIntegerTemplate(Entry $entry) {
        return 'integer';
    }

    /**
     * Get the rules for a boolean entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function booleanTemplate(Entry $entry) {
        return 'boolean';
    }

    /**
     * Get the rules for a date entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function dateTemplate(Entry $entry) {
        return 'date_format:Y-m-d';
    }

    /**
     * Get the rules for a dateTime entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function dateTimeTemplate(Entry $entry) {
        return 'date_format:Y-m-d H:i:s';
    }

    /**
     * Get the rules for a dateTimeTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function dateTimeTzTemplate(Entry $entry) {
        return 'date_format:Y-m-d H:i:s';
    }

    /**
     * Get the rules for a decimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function decimalTemplate(Entry $entry) {
        return 'numeric';
    }

    /**
     * Get the rules for a double entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function doubleTemplate(Entry $entry) {
        return 'numeric';
    }

    /**
     * Get the rules for a enum entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function enumTemplate(Entry $entry) {
        return 'in:' . implode(',', $entry->argument('allowed'));
    }

    /**
     * Get the rules for a float entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function floatTemplate(Entry $entry) {
        return 'numeric';
    }

    /**
     * Get the rules for a integer entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function integerTemplate(Entry $entry) {
        return 'integer|min:-2147483648|max:2147483647';
    }

    /**
     * Get the rules for a ipAddress entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function ipAddressTemplate(Entry $entry) {
        return 'ip';
    }

    /**
     * Get the rules for a json entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function jsonTemplate(Entry $entry) {
        return 'array';
    }

    /**
     * Get the rules for a jsonb entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function jsonbTemplate(Entry $entry) {
        return 'array';
    }

    /**
     * Get the rules for a macAddress entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function macAddressTemplate(Entry $entry) {
        return 'regex:^([0-9a-fA-F]{2}:){5}([0-9a-fA-F]{2})$';
    }

    /**
     * Get the rules for a mediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function mediumIntegerTemplate(Entry $entry) {
        return 'integer|min:-8388608|max:8388607';
    }

    /**
     * Get the rules for a smallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function smallIntegerTemplate(Entry $entry) {
        return 'integer|min:-32768|max:32767';
    }

    /**
     * Get the template for a softDeletes entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTemplate(Entry $entry) {
        return false;
    }

    /**
     * Get the template for a softDeletesTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTzTemplate(Entry $entry) {
        return false;
    }

    /**
     * Get the template for a timestamps entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTemplate(Entry $entry) {
        return false;
    }

    /**
     * Get the template for a timestampsTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTzTemplate(Entry $entry) {
        return false;
    }

    /**
     * Get the rules for a tinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function tinyIntegerTemplate(Entry $entry) {
        return 'integer|min:-128|max:127';
    }

    /**
     * Get the rules for a unsignedBigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function unsignedBigIntegerTemplate(Entry $entry) {
        return 'integer|min:0';
    }

    /**
     * Get the rules for a unsignedDecimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function unsignedDecimalTemplate(Entry $entry) {
        return 'numeric';
    }

    /**
     * Get the rules for a unsignedInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function unsignedIntegerTemplate(Entry $entry) {
        return 'integer|min:0|max:4294967295';
    }

    /**
     * Get the rules for a unsignedMediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function unsignedMediumIntegerTemplate(Entry $entry) {
        return 'integer|min:0|max:16777215';
    }

    /**
     * Get the rules for a unsignedSmallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function unsignedSmallIntegerTemplate(Entry $entry) {
        return 'integer|min:0|max:65535';
    }

    /**
     * Get the rules for a unsignedTinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function unsignedTinyIntegerTemplate(Entry $entry) {
        return 'integer|min:0|max:255';
    }

    /**
     * Get the rules for a uuid entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function uuidTemplate(Entry $entry) {
        return 'regex:^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$';
    }

    /**
     * Get the rules for a year entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The rules for the entry
     */
    public function yearTemplate(Entry $entry) {
        return 'regex:^\d{4}$';
    }

}
