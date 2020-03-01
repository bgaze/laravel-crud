<?php


namespace Bgaze\Crud\Support\Utils;


use Bgaze\Crud\Support\Crud\Crud;
use Exception;
use Illuminate\Filesystem\Filesystem;

class Helpers
{
    /**
     * Remove base_path() from a path string.
     *
     * @param  string  $path  The path of the file
     * @return string       The relative path of the file
     */
    public static function relativePath($path)
    {
        return str_replace(base_path() . '/', '', $path);
    }


    /**
     * Get the content of a stub file and populate it with CRUD variables.
     *
     * @param  Crud  $crud
     * @param  string  $string  The string to populate
     * @param  array  $variables  A set of variables to extend CRUD variables
     * @return string       The content of stub file
     * @throws Exception
     */
    public static function populateString(Crud $crud, $string, array $variables = [])
    {
        // Prepare variables list.
        $variables = array_merge($crud->getVariables(), $variables);

        // Return populated stub.
        return str_replace(array_keys($variables), array_values($variables), $string);
    }


    /**
     * Get the content of a stub file and populate it with CRUD variables.
     *
     * @param  Crud  $crud
     * @param  string  $name  The name of the stub
     * @param  array  $variables  A set of variables to extend CRUD variables
     * @return string       The content of stub file
     * @throws Exception
     */
    public static function populateStub(Crud $crud, $name, array $variables = [])
    {
        // Check that stub exists.
        $stubs = $crud->getCommand()->stubs();
        if (!isset($stubs[$name])) {
            throw new Exception("Undefined stub '{$name}'.");
        }

        // Get stub content.
        $stub = file_get_contents($stubs[$name]);

        // Return populated stub.
        return self::populateString($crud, $stub, $variables);
    }


    /**
     * Generate a file using a stub file.
     *
     * @param  string  $path  The path of the file relative to base_path()
     * @param  string  $content  The content of the file
     * @return string           The relative path of the file
     */
    public static function generateFile($path, $content)
    {
        /**
         * @var Filesystem
         */
        $fs = resolve(Filesystem::class);

        // Prepare file's paths.
        $relativePath = self::relativePath($path);
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
    public static function generatePhpFile($path, $content)
    {
        // Generate file.
        $relativePath = self::generateFile($path, $content);

        // Fix it with PhpCsFixer.
        php_cs_fixer($relativePath, ['--quiet' => true]);

        // Return file path.
        return $relativePath;
    }


    /**
     * Generate a formatted Blade template file using a stub.
     *
     * @param  string  $path  The path of the file relative to base_path()
     * @param  string  $content  The content of the file
     * @return string           The relative path of the file
     */
    public static function generateBladeFile($path, $content)
    {
        // Format Blade markup.
        $content = resolve(BladeFormatter::class)->indent($content);

        // Generate file.
        return self::generateFile($path, $content);
    }


    /**
     * Prepare value for PHP generation depending on it's type.
     *
     * @param  mixed  $value
     * @return string
     */
    public static function compileValueForPhp($value)
    {
        if (is_array($value)) {
            return self::compileArrayForPhp($value);
        }

        if ($value === true || $value === 'true') {
            return 'true';
        }

        if ($value === false || $value === 'false') {
            return 'false';
        }

        if ($value === null || $value === 'null') {
            return 'null';
        }

        if (!is_numeric($value)) {
            return "'" . addslashes($value) . "'";
        }

        return $value;
    }


    /**
     * Prepare array for PHP generation.
     *
     * @param  array  $array
     * @param  bool|null  $assoc
     * @return string
     */
    public static function compileArrayForPhp($array, $assoc = null)
    {
        if ($assoc === null) {
            $assoc = (count(array_filter(array_keys($array), 'is_string')) > 0);
        }

        $entries = collect($array)->map(function ($value, $key) use ($assoc) {
            if ($assoc) {
                return self::compileValueForPhp($key) . ' => ' . self::compileValueForPhp($value);
            }

            return self::compileValueForPhp($value);
        });

        return '[' . $entries->implode(', ') . ']';
    }

}