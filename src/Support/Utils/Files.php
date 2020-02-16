<?php


namespace Bgaze\Crud\Support\Utils;


use Illuminate\Filesystem\Filesystem;

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


    /**
     * Generate a file using a stub file.
     *
     * @param  string  $path  The path of the file relative to base_path()
     * @param  string  $content  The content of the file
     * @return string           The relative path of the file
     */
    protected function generateFile($path, $content)
    {
        /**
         * @var Filesystem
         */
        $fs = resolve(Filesystem::class);

        // Prepare file's paths.
        $relativePath = $this->relativePath($path);
        $absolutePath = base_path($relativePath);

        // Create output dir if necessary.
        if (!$fs->isDirectory(dirname($absolutePath))) {
            $fs->makeDirectory(dirname($absolutePath), 0777, true, true);
        }

        // Create file.
        $fs->put($absolutePath, $content);

        // Return file path.
        return $relativePath;
    }


    /**
     * Generate a file using a stub file then fix it using PHP-CS-Fixer.
     *
     * @param  string  $path  The path of the file relative to base_path()
     * @param  string  $content  The content of the file
     * @return string           The relative path of the file
     */
    protected function generatePhpFile($path, $content)
    {
        // Generate file.
        $relativePath = $this->generateFile($path, $content);

        // Fix it with PhpCsFixer.
        php_cs_fixer($relativePath, ['--quiet' => true]);

        // Return file path.
        return $relativePath;
    }

}