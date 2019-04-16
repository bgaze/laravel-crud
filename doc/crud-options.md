[< Back](../README.md#table-of-content)

# CRUD command options

Any step of the CRUD generation process can be set using options.  
Any option directly passed to the command will be skipped by the wizard.

A very good way to work is first to prepare all your app Models as CRUD commands, so you can focus on your application structure and database shema.  
Then you just need to run prepared commands to get a working base application.

## Example

CRUD generation without any interractions:

```
php artisan crud:classic Article -n -s=none \
-c "string title" \
-c "enum category foo bar foobar -i" \
-c "text body -n" \
-c "unsignedInteger views -d 0" \
-c "boolean active -d 1" \
&& php artisan migrate \
&& php artisan db:seed --class=ArticlesTableSeeder 
```

This command will:

* Create a working CRUD for an **articles** table containing following fields:
    * **title:** required string
    * **category:** required indexed enum with `foo`, `bar` and `foobar` as values.
    * **body:**: nullable text
    * **views:** required unsigned integer with `0` as default value.
    * **active:** required boolean
* Create the **articles** table into database.
* Seed 50 fake entries into **articles** table.

## Available options

All the CRUD commands have the same signature:

```
Usage:
  crud:classic [options] [--] <model>

Arguments:
  model                              The FullName of the Model

Options:
  -p, --plurals[=PLURALS]            The Plurals version of the Model's name
  -t, --timestamps[=TIMESTAMPS]      Add timestamps directives
  -s, --soft-deletes[=SOFT-DELETES]  Add soft delete directives
  -c, --content[=CONTENT]            The list of Model\'s fields using SignedInput syntax (multiple values allowed)
  -o, --only[=ONLY]                  Generate only selected files (multiple values allowed)
  -l, --layout[=LAYOUT]              The layout to extend into generated views
  -h, --help                         Display this help message
  -q, --quiet                        Do not output any message
  -V, --version                      Display this application version
      --ansi                         Force ANSI output
      --no-ansi                      Disable ANSI output
  -n, --no-interaction               Do not ask any interactive question
      --env[=ENV]                    The environment the command should run under
  -v|vv|vvv, --verbose               Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
