<?php

namespace Bgaze\Crud\Support;

trait ConsoleHelpersTrait {

    public function h1($text, $bg = 'blue', $fg = 'white') {
        $this->line("\n<fg=$fg;bg=$bg>" . str_repeat(" ", 80) . "\n" . str_pad(" $text", 80) . "\n" . str_repeat(" ", 80) . "</>\n");
    }

    public function h2($text, $bg = 'blue', $fg = 'white') {
        $this->line("\n<fg=$fg;bg=$bg>" . str_pad(" $text", 80) . "</>\n");
    }

}
