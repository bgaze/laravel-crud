<?php

namespace Bgaze\Crud\Support;

trait ConsoleHelpersTrait {

    /**
     * TODO
     * 
     * @return \Bgaze\Crud\Support\Theme\Crud
     */
    public function getTheme() {
        return $this->laravel->make($this->option('theme') ?: config('crud.theme'), [
                    'model' => $this->argument('model'),
                    'plural' => $this->option('plural')
        ]);
    }

    /**
     * Display a level 1 title.
     * 
     * @param string $text
     */
    public function h1($text) {
        $this->nl();
        $this->line("<fg=white;bg=blue>" . str_repeat(" ", 80) . "</>");
        $this->line("<fg=white;bg=blue>" . str_pad(strtoupper(" $text"), 80) . "</>");
        $this->line("<fg=white;bg=blue>" . str_repeat(" ", 80) . "</>");
        $this->nl();
    }

    /**
     * Display a level 2 title.
     * 
     * @param string $text
     */
    public function h2($text) {
        $this->line("<fg=blue>" . strtoupper($text) . "</>");
        $this->nl();
    }

    /**
     * Display a list.
     * 
     * @param array $items
     * @param string $color
     * @param integer $indent
     */
    public function ul(array $items, $color = 'cyan', $indent = 2) {
        foreach ($items as $i) {
            $this->line(str_repeat(' ', $indent) . "<fg=$color>* $i</>");
        }
    }

    /**
     * Displays new line(s).
     * 
     * @param integer $multiplier
     */
    public function nl($multiplier = 1) {
        $this->line(str_repeat("\n", $multiplier - 1));
    }

}
