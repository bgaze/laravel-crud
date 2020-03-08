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
        // Remove trailing empty arguments.
        $arguments = collect($entry->arguments());
        while ($arguments->isNotEmpty() && $arguments->last() === null) {
            $arguments->pop();
        }

        // Compile values for PHP.
        $arguments = $arguments->map(function ($argument) {
            return Helpers::compileValueForPhp($argument);
        });

        // Generate migration line and add modifiers.
        $template = '$table->' . $entry->command() . '(' . $arguments->implode(', ') . ')';
        $this->addModifiers($entry, $template);

        return $template . ';';
    }
}