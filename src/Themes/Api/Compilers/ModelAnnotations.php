<?php

namespace Bgaze\Crud\Themes\Api\Compilers;

use Bgaze\Crud\Core\Compiler;
use Bgaze\Crud\Core\Entry;

/**
 * Description of ModelDocMethod
 *
 * @author bgaze
 */
class ModelAnnotations extends Compiler {

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
     * Generate a PhpDocumentor annotation for an entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function compileDefault(Entry $entry) {
        return $this->property('string', $entry->name());
    }

    /**
     * Generate a PhpDocumentor annotation for a bigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function bigInteger(Entry $entry) {
        return $this->integer($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a boolean entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function boolean(Entry $entry) {
        return $this->property('boolean', $entry->name());
    }

    /**
     * Generate a PhpDocumentor annotation for a date entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function date(Entry $entry) {
        return $this->property('\Carbon\Carbon', $entry->name());
    }

    /**
     * Generate a PhpDocumentor annotation for a dateTime entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTime(Entry $entry) {
        return $this->date($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a dateTimeTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function dateTimeTz(Entry $entry) {
        return $this->date($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a decimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function decimal(Entry $entry) {
        return $this->float($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a double entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function double(Entry $entry) {
        return $this->float($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a float entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function float(Entry $entry) {
        return $this->property('float', $entry->name());
    }

    /**
     * Generate a PhpDocumentor annotation for a integer entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function integer(Entry $entry) {
        return $this->property('integer', $entry->name());
    }

    /**
     * Generate a PhpDocumentor annotation for a json entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function json(Entry $entry) {
        return $this->property('array', $entry->name());
    }

    /**
     * Generate a PhpDocumentor annotation for a jsonb entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function jsonb(Entry $entry) {
        return $this->json($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a mediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function mediumInteger(Entry $entry) {
        return $this->integer($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a morphs entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function morphs(Entry $entry) {
        return $this->property('integer', $entry->name() . '_id') . "\n" . $this->property('string', $entry->name() . '_type');
    }

    /**
     * Generate a PhpDocumentor annotation for a nullableMorphs entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function nullableMorphs(Entry $entry) {
        return $this->morphs($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a smallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function smallInteger(Entry $entry) {
        return $this->integer($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a softDeletes entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletes(Entry $entry) {
        return $this->property('\Carbon\Carbon', 'deleted_at');
    }

    /**
     * Generate a PhpDocumentor annotation for a softDeletesTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function softDeletesTz(Entry $entry) {
        return $this->softDeletes($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a time entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function time(Entry $entry) {
        return $this->date($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a timeTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timeTz(Entry $entry) {
        return $this->date($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a timestamp entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestamp(Entry $entry) {
        return $this->date($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a timestampTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampTz(Entry $entry) {
        return $this->date($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a timestamps entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestamps(Entry $entry) {
        return $this->property('\Carbon\Carbon', 'created_at') . "\n" . $this->property('\Carbon\Carbon', 'updated_at');
    }

    /**
     * Generate a PhpDocumentor annotation for a timestampsTz entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function timestampsTz(Entry $entry) {
        return $this->timestamps($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a tinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function tinyInteger(Entry $entry) {
        return $this->integer($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a unsignedBigInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedBigInteger(Entry $entry) {
        return $this->integer($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a unsignedDecimal entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedDecimal(Entry $entry) {
        return $this->float($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a unsignedInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedInteger(Entry $entry) {
        return $this->integer($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a unsignedMediumInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedMediumInteger(Entry $entry) {
        return $this->integer($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a unsignedSmallInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedSmallInteger(Entry $entry) {
        return $this->integer($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a unsignedTinyInteger entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function unsignedTinyInteger(Entry $entry) {
        return $this->integer($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a year entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function year(Entry $entry) {
        return $this->date($entry);
    }

    /**
     * Generate a PhpDocumentor annotation for a hasOne relation.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The relation 
     * @return string The template for the relation
     */
    public function hasOne(Entry $entry) {
        $name = $entry->option('method', $entry->related()->getModelCamel());
        return $this->property('\\' . $entry->related()->getModelClass(), $name);
    }

    /**
     * Generate a PhpDocumentor annotation for a hasMany relation.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The relation 
     * @return string The template for the relation
     */
    public function hasMany(Entry $entry) {
        $name = $entry->option('method', $entry->related()->getPluralCamel());
        return $this->property('\\' . $entry->related()->getModelClass(), $name);
    }

    /**
     * Generate a PhpDocumentor annotation for a belongsTo relation.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The relation 
     * @return string The template for the relation
     */
    public function belongsTo(Entry $entry) {
        return $this->hasOne($entry);
    }

}
