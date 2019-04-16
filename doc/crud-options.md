[< Back](../README.md#table-of-content)

# CRUD command options

Any step of the CRUD generation process can be set using options.  
Any option directly passed to the command will be skipped by the wizard.

> A very good way to work is to first focus on your application database shema by preparing all your application Models as CRUD commands.  
> Then you just need to run prepared commands to get a working base application.

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
Arguments:
  model                   The FullName of the Model

Options:
  -p, --plurals          The Plurals version of the Model's name
  -t, --timestamps       Add timestamps directive
  -s, --soft-deletes     Add soft delete directive
  -c, --content          The list of Model's fields using SignedInput syntax (multiple values allowed)
  -o, --only             Generate only selected files (multiple values allowed)
  -l, --layout           The layout to extend into generated views
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env              The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages
```

**model:**

The FullName of the model: the model's name including namespace without the `App` part.  
This is the only required agrument to CRUD commands.

**plurals:**

Model's FullName with each segments pluralized.  
Plurals is automatically set/suggested based on FullName and english language.

**timestamps:**

The timestamps to add to the model.  
Allowed values are: `timestamps`, `timestampsTz`, `nullableTimestamps`, `none`  
Default is: `timestamps`

**soft-deletes:**

The softDelete to add to the model.  
Allowed values are: `softDeletes`, `softDeletesTz`, `none`  
Default is: `softDeletes`

**content:**

The model's table fields described using SignedInput syntax.

**only:**

> Allowed values depend on CRUD theme and are provided into command's help.

Generate only selected files.

* Allowed values for _crud:api_ theme:  
`migration-class`, ` model-class`, ` factory-file`, ` seeds-class`, ` request-class`, ` resource-class`, ` controller-class`
* Allowed values for _crud:classic_ theme:  
`migration-class`, ` model-class`, ` factory-file`, ` seeds-class`, ` request-class`, ` resource-class`, ` controller-class`, ` index-view`, ` create-view`, ` edit-view`, ` show-view`

**layout:**

> Please note that this options has no effect for themes that don't create views (like _crud:api_).

The layout to extend into generated views.  
Default value is provided by the theme and displayed into command's help.

Default value for _crud:classic_ theme: `crud-classic::layout`
