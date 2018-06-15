<?php

namespace Bgaze\Crud\Support;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * A collection of display helpers for console applications.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
trait ConsoleHelpersTrait {

    /**
     * TODO
     */
    protected function setCustomStyles() {
        $this->output->getFormatter()->setStyle('h1', new OutputFormatterStyle('white', 'blue'));
        $this->output->getFormatter()->setStyle('h2', new OutputFormatterStyle('blue', null, array('bold')));
    }

    /**
     * Display a level 1 title.
     * 
     * @param string $text The text to display
     * @param boolean $test Nothing is displayed if test fails
     */
    public function h1($text, $test = true) {
        if ($test) {
            $this->nl();
            $this->line("<h1>" . str_repeat(" ", 80) . "</h1>");
            $this->line("<h1>" . str_pad(strtoupper(" $text"), 80) . "</h1>");
            $this->line("<h1>" . str_repeat(" ", 80) . "</h1>");
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
            $this->line(" <h2>" . strtoupper($text) . "</h2>");
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
            $this->line(" <info>{$dt}:</info> {$dd}");
        }
    }

}
