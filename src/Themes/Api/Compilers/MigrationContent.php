<?php

namespace Bgaze\Crud\Themes\Api\Compilers;

use Bgaze\Crud\Definitions;
use Bgaze\Crud\Core\Compiler;
use Bgaze\Crud\Core\Entry;

/**
 * Description of MigrationEntry
 *
 * @author bgaze
 */
class MigrationContent extends Compiler {

    /**
     * Add modifiers to template based on entry options and user input.
     * 
     * @param Bgaze\Crud\Core\Entry $entry
     * @param string $template
     */
    protected function addModifiers(Entry $entry, &$template) {
        foreach ($entry->options() as $k => $v) {
            if ($v !== null && $v !== false && isset(Definitions::COLUMNS_MODIFIERS[$k])) {
                $template .= str_replace('%value', $this->compileValueForPhp($v), Definitions::COLUMNS_MODIFIERS[$k]);
            }
        }
    }

    /**
     * Get the default compilation function for an entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The compiled entry
     */
    public function compileDefault(Entry $entry) {
        $arguments = $entry->definition()->getArguments();

        if (!empty($arguments)) {
            $template = '$table->' . $entry->command() . '(%' . implode(', %', array_keys($arguments)) . ')';
        } else {
            $template = '$table->' . $entry->command() . '()';
        }

        foreach ($entry->arguments() as $k => $v) {
            $template = str_replace("%$k", $this->compileValueForPhp($v), $template);
        }

        $this->addModifiers($entry, $template);

        return $template . ';';
    }

    /**
     * Compile a hasOne entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function hasOne(Entry $entry) {
        return null;
    }

    /**
     * Compile a hasMany entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function hasMany(Entry $entry) {
        return null;
    }

    /**
     * Compile a belongsTo entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The template for the entry
     */
    public function belongsTo(Entry $entry) {
        $column = $entry->option('foreignKey', $entry->related()->getModelCamel() . '_id');
        $template = '$table->unsignedInteger(' . $this->compileValueForPhp($column) . ')';
        $this->addModifiers($entry, $template);
        return $template . ';';
    }

}
