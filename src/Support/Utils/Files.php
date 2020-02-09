<?php


namespace Bgaze\Crud\Support\Utils;


use Exception;

trait Files
{
    /**
     * Remove base_path() from a path string.
     *
     * @param  string  $path  The path of the file
     * @return string       The relative path of the file
     */
    protected function relativePath($path)
    {
        return str_replace(base_path() . '/', '', $path);
    }
}