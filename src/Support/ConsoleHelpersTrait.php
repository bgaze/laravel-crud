<?php

namespace Bgaze\Crud\Support;

/**
 * A collection of display helpers for console applications.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
trait ConsoleHelpersTrait {

    /**
     * Display a level 1 title.
     * 
     * @param string $text The text to display
     * @param boolean $test Nothing is displayed if test fails
     */
    public function h1($text, $test = true) {
        if ($test) {
            $this->nl();
            $this->line("<fg=white;bg=blue>" . str_repeat(" ", 80) . "</>");
            $this->line("<fg=white;bg=blue>" . str_pad(strtoupper(" $text"), 80) . "</>");
            $this->line("<fg=white;bg=blue>" . str_repeat(" ", 80) . "</>");
            $this->nl();
        }
    }

    /**
     * Display a level 2 title.
     * 
     * @param string $text
     * @param boolean $test Nothing is displayed if test fails
     */
    public function h2($text, $test = true) {
        if ($test) {
            $this->line(" <fg=blue>" . strtoupper($text) . "</>");
            $this->nl();
        }
    }

    /**
     * Displays a new line.
     * 
     * @param string $text The text to display
     * @param boolean $test Nothing is displayed if test fails
     */
    public function nl($test = true) {
        if ($test) {
            echo "\n";
        }
    }

    /**
     * Display a definition.
     * 
     * @param string $dt The label of definition
     * @param string $dd The value of definition
     * @param boolean $test Nothing is displayed if test fails
     */
    public function dl($dt, $dd, $test = true) {
        if ($test) {
            $this->info(" {$dt} : <fg=white>{$dd}</>");
        }
    }

}
