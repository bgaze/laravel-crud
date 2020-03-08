<?php


namespace Bgaze\Crud\Themes\Api\Compilers;

use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Tasks\Compiler;
use Bgaze\Crud\Support\Utils\Helpers;
use Exception;
use Illuminate\Database\Schema\Builder;

class FactoryContent extends Compiler
{

    /**
     * Generate a factory line.
     *
     * @param  string  $name  The key to populate
     * @param  string  $faker  The php statement to generate data
     * @return string
     */
    protected function factoryGroup($name, $faker)
    {
        if (empty($faker)) {
            return "// TODO: {$name}";
        }

        return "'{$name}' => {$faker},";
    }


    /**
     * Get the default compilation function for an entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The compiled entry
     * @throws Exception
     */
    public function default(Entry $entry)
    {
        if ($entry->isIndex()) {
            return null;
        }

        return collect($entry->columns())->map(function ($column) {
            return $this->factoryGroup($column, null);
        })->all();
    }



    /**
     * Run a compiler against all CRUD entries.
     *
     * @param  string  $onEmpty  A replacement value if result is empty
     * @return string
     */
    public function compile($onEmpty = '')
    {
        $content = $this->crud->getContent()
            ->map(function (Entry $entry) {
                return $this->{$entry->command()}($entry);
            })
            ->flatten()
            ->filter()
            ->sort(function($a, $b){
                $pa = (strpos($a,'TODO') !== false);
                $pb = (strpos($b,'TODO') !== false);

                if ($pa && !$pb) {
                    return 1;
                }

                if (!$pa && $pb) {
                    return -1;
                }

                return 0;
            })
            ->implode(PHP_EOL);

        if (empty($content)) {
            return $onEmpty;
        }

        return $content;
    }

    /**
     * Compile a bigInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function bigInteger(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(-2 ** 63, 2 ** 63 - 1)');
    }


    /**
     * Compile a boolean entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function boolean(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->boolean');
    }


    /**
     * Get the factory for a char entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array|array The rules for the entry
     * @throws Exception
     */
    public function char(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), sprintf('Str::random(%s)', $entry->option('length', Builder::$defaultStringLength)));
    }


    /**
     * Compile a date entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function date(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->date');
    }


    /**
     * Compile a dateTime entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function dateTime(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), "\$faker->date('Y-m-d H:i:s')");
    }


    /**
     * Compile a dateTimeTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function dateTimeTz(Entry $entry)
    {
        return $this->dateTime($entry);
    }


    /**
     * Compile a decimal entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function decimal(Entry $entry)
    {
        return $this->float($entry);
    }


    /**
     * Compile a double entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function double(Entry $entry)
    {
        return $this->float($entry);
    }


    /**
     * Compile a enum entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function enum(Entry $entry)
    {
        $choices = $entry->argument('allowed');
        if ($entry->option('nullable')) {
            array_unshift($choices, null);
        }
        return $this->factoryGroup($entry->name(), '$faker->randomElement(' . Helpers::compileArrayForPhp($choices) . ')');
    }


    /**
     * Compile a float entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function float(Entry $entry)
    {
        $decimals = $entry->argument('places');
        $max = str_repeat(9, $entry->argument('total') - $decimals);
        return $this->factoryGroup($entry->name(), sprintf('$faker->randomFloat(%s, 0, %s)', $decimals, $max));
    }


    /**
     * Compile a integer entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function integer(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(-2147483648, 2147483647)');
    }


    /**
     * Compile a ipAddress entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function ipAddress(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->ipv4');
    }


    /**
     * Compile a json entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function json(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), 'json_encode($faker->sentences(mt_rand(3, 6)))');
    }


    /**
     * Compile a jsonb entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function jsonb(Entry $entry)
    {
        return $this->json($entry);
    }


    /**
     * Compile a longText entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function longText(Entry $entry)
    {
        return $this->text($entry);
    }


    /**
     * Compile a macAddress entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function macAddress(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->macAddress');
    }


    /**
     * Compile a mediumInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function mediumInteger(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(-8388608, 8388607)');
    }


    /**
     * Compile a mediumText entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function mediumText(Entry $entry)
    {
        return $this->text($entry);
    }


    /**
     * Compile a rememberToken entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function rememberToken(Entry $entry)
    {
        return $this->factoryGroup('remember_token', 'Str::random(100)');
    }


    /**
     * Compile a set entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function set(Entry $entry)
    {
        $choices = $entry->argument('allowed');
        $min = $entry->option('nullable') ? 0 : 1;
        $faker = sprintf('$faker->randomElements(%s, mt_rand(%s, %s))', Helpers::compileArrayForPhp($choices), $min, count($choices));

        return $this->factoryGroup($entry->name(), $faker);
    }


    /**
     * Compile a smallInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function smallInteger(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(-32768, 32767)');
    }


    /**
     * Compile a softDeletes entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function softDeletes(Entry $entry)
    {
        return null;
    }


    /**
     * Compile a softDeletesTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function softDeletesTz(Entry $entry)
    {
        return null;
    }


    /**
     * Get the factory for a string entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array|array The rules for the entry
     * @throws Exception
     */
    public function string(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), sprintf('$faker->text(%s)', $entry->option('length', Builder::$defaultStringLength)));
    }


    /**
     * Compile a text entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function text(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->text(1000)');
    }


    /**
     * Compile a time entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function time(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->time');
    }


    /**
     * Compile a timeTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timeTz(Entry $entry)
    {
        return $this->time($entry);
    }


    /**
     * Compile a timestamp entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestamp(Entry $entry)
    {
        return $this->dateTime($entry);
    }


    /**
     * Compile a timestampTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestampTz(Entry $entry)
    {
        return $this->dateTime($entry);
    }


    /**
     * Compile a timestamps entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestamps(Entry $entry)
    {
        return null;
    }


    /**
     * Compile a timestampsTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestampsTz(Entry $entry)
    {
        return null;
    }


    /**
     * Compile a tinyInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function tinyInteger(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(-128, 127)');
    }


    /**
     * Compile a unsignedBigInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedBigInteger(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(0, 2 ** 64 -1)');
    }


    /**
     * Compile a unsignedDecimal entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function unsignedDecimal(Entry $entry)
    {
        return $this->float($entry);
    }


    /**
     * Compile a unsignedInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedInteger(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(0, 4294967295)');
    }


    /**
     * Compile a unsignedMediumInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedMediumInteger(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(0, 16777215)');
    }


    /**
     * Compile a unsignedSmallInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedSmallInteger(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(0, 65535)');
    }


    /**
     * Compile a unsignedTinyInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function unsignedTinyInteger(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(0, 255)');
    }


    /**
     * Compile a uuid entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function uuid(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->uuid');
    }


    /**
     * Compile a year entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function year(Entry $entry)
    {
        return $this->factoryGroup($entry->name(), '$faker->numberBetween(1900, 2100)');
    }

}