<?php


namespace Bgaze\Crud\Themes\Api\Compilers;


use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Tasks\Compiler;
use Exception;
use Illuminate\Database\Schema\Builder;

class RequestRules extends Compiler
{
    /**
     * Compile content to request class body line.
     *
     * @param  Entry  $entry  The entry
     * @param  string  $rule  The entry rules
     * @param  string|null  $name
     * @return string
     * @throws Exception
     */
    protected function requestGroup(Entry $entry, $rule = null, $name = null)
    {
        $definition = $entry->definition();

        if ($definition->hasOption('nullable')) {
            $rules[] = $entry->option('nullable') ? 'nullable' : 'required';
        } elseif (preg_match('/^nullable/', $entry->command())) {
            $rules[] = 'nullable';
        } else {
            $rules[] = 'required';
        }

        if (!empty($rule)) {
            $rules[] = $rule;
        }

        if (in_array('unique', $definition->getOptions()) && $definition->getOption('unique')) {
            $rules[] = 'unique:' . $this->crud->TableName . ',' . $entry->name();
        }

        return sprintf("'%s' => '%s',", $name ?: $entry->name(), implode('|', $rules));
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

        return $this->requestGroup($entry);
    }


    /**
     * Get the rules for a bigInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function bigInteger(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer');
    }


    /**
     * Get the rules for a boolean entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function boolean(Entry $entry)
    {
        return $this->requestGroup($entry, 'boolean');
    }


    /**
     * Get the rules for a char entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function char(Entry $entry)
    {
        return $this->requestGroup($entry, 'string|max:' . $entry->option('length', Builder::$defaultStringLength));
    }


    /**
     * Get the rules for a date entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function date(Entry $entry)
    {
        return $this->requestGroup($entry, 'date_format:Y-m-d');
    }


    /**
     * Get the rules for a dateTime entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function dateTime(Entry $entry)
    {
        return $this->requestGroup($entry, 'date_format:Y-m-d H:i:s');
    }


    /**
     * Get the rules for a dateTimeTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function dateTimeTz(Entry $entry)
    {
        return $this->requestGroup($entry, 'date_format:Y-m-d H:i:s');
    }


    /**
     * Get the rules for a decimal entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function decimal(Entry $entry)
    {
        return $this->requestGroup($entry, 'numeric');
    }


    /**
     * Get the rules for a double entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function double(Entry $entry)
    {
        return $this->requestGroup($entry, 'numeric');
    }


    /**
     * Get the rules for a enum entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function enum(Entry $entry)
    {
        return $this->requestGroup($entry, 'in:' . implode(',', $entry->argument('allowed')));
    }


    /**
     * Get the rules for a float entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function float(Entry $entry)
    {
        return $this->requestGroup($entry, 'numeric');
    }


    /**
     * Get the rules for a integer entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function integer(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer|min:-2147483648|max:2147483647');
    }


    /**
     * Get the rules for a ipAddress entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function ipAddress(Entry $entry)
    {
        return $this->requestGroup($entry, 'ip');
    }


    /**
     * Get the rules for a json entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function json(Entry $entry)
    {
        return $this->requestGroup($entry, 'json');
    }


    /**
     * Get the rules for a jsonb entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function jsonb(Entry $entry)
    {
        return $this->requestGroup($entry, 'json');
    }


    /**
     * Get the rules for a macAddress entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function macAddress(Entry $entry)
    {
        return $this->requestGroup($entry, 'regex:^([0-9a-fA-F]{2}:){5}([0-9a-fA-F]{2})$');
    }


    /**
     * Get the rules for a mediumInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function mediumInteger(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer|min:-8388608|max:8388607');
    }


    /**
     * Get the rules for a morphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return array The rules for the entry
     * @throws Exception
     */
    public function morphs(Entry $entry)
    {
        return [
            $this->requestGroup($entry, 'integer|min:0', $entry->argument('name') . '_id'),
            $this->requestGroup($entry, null, $entry->argument('name') . '_type'),
        ];
    }


    /**
     * Get the rules for a nullableMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return array The rules for the entry
     * @throws Exception
     */
    public function nullableMorphs(Entry $entry)
    {
        return $this->morphs($entry);
    }


    /**
     * Get the rules for a nullableUuidMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return array The rules for the entry
     * @throws Exception
     */
    public function nullableUuidMorphs(Entry $entry)
    {
        return $this->uuidMorphs($entry);
    }


    /**
     * Get the rules for a smallInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function smallInteger(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer|min:-32768|max:32767');
    }


    /**
     * Get the rules for a rememberToken entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function rememberToken(Entry $entry)
    {
        return "'remember_token' => 'nullable|string|max:100',";
    }


    /**
     * Get the rules for a set entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function set(Entry $entry)
    {
        return [
            $this->requestGroup($entry, 'array'),
            sprintf("'%s.*' => 'distinct|in:%s',", $entry->name(), implode(',', $entry->argument('allowed')))
        ];
    }


    /**
     * Get the rules for a softDeletes entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function softDeletes(Entry $entry)
    {
        return null;
    }


    /**
     * Get the rules for a softDeletesTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function softDeletesTz(Entry $entry)
    {
        return $this->softDeletes($entry);
    }


    /**
     * Get the rules for a string entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function string(Entry $entry)
    {
        return $this->requestGroup($entry, 'string|max:' . $entry->option('length', Builder::$defaultStringLength));
    }


    /**
     * Get the rules for a time entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function time(Entry $entry)
    {
        return $this->requestGroup($entry, 'date_format:H:i:s');
    }


    /**
     * Get the rules for a timeTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function timeTz(Entry $entry)
    {
        return $this->time($entry);
    }


    /**
     * Get the rules for a timestamp entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function timestamp(Entry $entry)
    {
        return $this->dateTime($entry);
    }


    /**
     * Get the rules for a timestampTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     * @throws Exception
     */
    public function timestampTz(Entry $entry)
    {
        return $this->dateTimeTz($entry);
    }


    /**
     * Get the rules for a timestamps entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestamps(Entry $entry)
    {
        return null;
    }


    /**
     * Get the rules for a timestampsTz entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The template for the entry
     */
    public function timestampsTz(Entry $entry)
    {
        return $this->timestamps($entry);
    }


    /**
     * Get the rules for a tinyInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function tinyInteger(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer|min:-128|max:127');
    }


    /**
     * Get the rules for a unsignedBigInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function unsignedBigInteger(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer|min:0');
    }


    /**
     * Get the rules for a unsignedDecimal entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function unsignedDecimal(Entry $entry)
    {
        return $this->requestGroup($entry, 'numeric|min:0');
    }


    /**
     * Get the rules for a unsignedInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function unsignedInteger(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer|min:0|max:4294967295');
    }


    /**
     * Get the rules for a unsignedMediumInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function unsignedMediumInteger(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer|min:0|max:16777215');
    }


    /**
     * Get the rules for a unsignedSmallInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function unsignedSmallInteger(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer|min:0|max:65535');
    }


    /**
     * Get the rules for a unsignedTinyInteger entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function unsignedTinyInteger(Entry $entry)
    {
        return $this->requestGroup($entry, 'integer|min:0|max:255');
    }


    /**
     * Get the rules for a uuid entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function uuid(Entry $entry)
    {
        return $this->requestGroup($entry, 'uuid');
    }


    /**
     * Get the rules for a uuidMorphs entry.
     *
     * @param  Entry  $entry  The entry
     * @return array The rules for the entry
     * @throws Exception
     */
    public function uuidMorphs(Entry $entry)
    {
        return [
            $this->requestGroup($entry, 'uuid', $entry->argument('name') . '_id'),
            $this->requestGroup($entry, null, $entry->argument('name') . '_type'),
        ];
    }


    /**
     * Get the rules for a year entry.
     *
     * @param  Entry  $entry  The entry
     * @return string|array The rules for the entry
     * @throws Exception
     */
    public function year(Entry $entry)
    {
        return $this->requestGroup($entry, 'date_format:Y');
    }
}