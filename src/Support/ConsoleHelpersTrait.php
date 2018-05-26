<?php

namespace Bgaze\Crud\Support;

trait ConsoleHelpersTrait {

    /**
     * Display a level 1 title.
     * 
     * @param string $text
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
     */
    public function h2($text, $test = true) {
        if ($test) {
            $this->line(" <fg=blue>" . strtoupper($text) . "</>");
            $this->nl();
        }
    }

    /**
     * Displays new line(s).
     * 
     * @param integer $multiplier
     */
    public function nl($test = true) {
        if ($test) {
            echo "\n";
        }
    }

    public function dl($dt, $dd) {
        $this->info(" {$dt} : <fg=white>{$dd}</>");
    }

}
