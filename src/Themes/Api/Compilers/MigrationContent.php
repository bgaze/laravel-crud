<?php


namespace Bgaze\Crud\Themes\Api\Compilers;


use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Definitions;
use Bgaze\Crud\Support\Tasks\Compiler;
use Bgaze\Crud\Support\Utils\Helpers;
use Exception;

class MigrationContent extends Compiler
{
    /**
     * Add modifiers to template based on entry options and user input.
     *
     * @param  Entry  $entry
     * @param  string  $template
     * @throws Exception
     */
    protected function addModifiers(Entry $entry, &$template)
    {
        foreach ($entry->options() as $k => $v) {
            if ($v !== null && $v !== false && isset(Definitions::COLUMNS_MODIFIERS[$k])) {
                $template .= str_replace('%value', Helpers::compileValueForPhp($v), Definitions::COLUMNS_MODIFIERS[$k]);
            }
        }
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
        $arguments = $entry->definition()->getArguments();

        if (!empty($arguments)) {
            $template = '$table->' . $entry->command() . '(%' . implode(', %', array_keys($arguments)) . ')';
        } else {
            $template = '$table->' . $entry->command() . '()';
        }

        foreach ($entry->arguments() as $k => $v) {
            $template = str_replace("%$k", Helpers::compileValueForPhp($v), $template);
        }

        $this->addModifiers($entry, $template);

        return $template . ';';
    }
}