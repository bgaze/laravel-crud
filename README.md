> Documentation in progress ;-)

# bgaze/laravel-crud

<p align="center">
  <img src="doc/assets/demo.png">
</p>

## Demo

[This short video on Vimeo](https://vimeo.com/330304646) shows the creation of a CRUD for an **Article** model containing following fields:

* **category:** indexed enum field, with _foo_ and _bar_ as values.
* **title:** a mandatory string field.
* **body:** a nullable text field.
* **active:** a boolean field with _0_ as default value.
* Timestamps and softDeletes fields.

## Overview

This package allows to generate CRUDs in a breath for your Laravel 5.5+ applications.

Using the command signature syntax, it offers a handy way to define the model fields.

It is designed to be easily extended in order to create custom CRUD (aka _themes_).  
Each theme is available as a dedicated console command.

Two themes are provided :

* **crud:classic** generates a fully fonctionnal "classic" CRUD, creating for you : migration, model, factory, seeder, request, resource, controller, views and routes.
* **crud:api:** generates a fully fonctionnal REST API CRUD, creating for you : migration, model, factory, seeder, request, resource, controller and routes.

## Installation

Simply import the package into your Laravel application:

```
composer require bgaze/laravel-crud
```

You can publish the package configuration to `/config/crud.php`:

```
php artisan vendor:publish --provider=Bgaze\Crud\ServiceProvider
```

And classic themes views to `/resources/views/vendor/crud-classic`:

```
php artisan vendor:publish --tag=crud-classic-views
```

## Important concepts

> This section explain very important concepts required to use the package.  
> Please read it carrefully.

### FullName and Plurals

As a convention, we designate by:

* **FullName:** the model's name including namespace without the `App` part. 
* **Plurals:** the pluralized form of each segment of the FullName.

When a CRUD command is invoked, the only required argument is model's FullName, and Plurals is automatically generated based on english language. 
 
A confirmation is asked for Plurals, please pay attention to that important step and correct the proposed value if needed.

**Examples:**

```
// Models:
    \App\MyGrandParent  
    \App\MyGrandParent\MyParent  
    \App\MyGrandParent\MyParent\MyChild

// FullName:
    MyGrandParent  
    MyGrandParent\MyParent  
    MyGrandParent\MyParent\MyChild

// Plurals:
    MyGrandParents  
    MyGrandParents\MyParents  
    MyGrandParents\MyParents\MyChildren
```

### SignedInput

When dealing with CRUD, a tricky part is often to define model's properties (aka table fields).

I believe that the Laravel commands signature syntax is a very great to do that in a concise and handy way.  
So I've kinda "hacked" it to make that step as easy as possible.

Please note that you can also use that trick for your own needs using the `Bgaze\Crud\Support\SignedInput` class.

<details><summary><b>Examples</b></summary><p>

Adding a _foo_ integer field, nullable and indexed:

```
// Input:
integer foo -n -i

// Result:
$table->integer('foo')->nullable()->index();
```

Adding a _bar_ varchar field, with a length of 100 and a unique constraint:

```
// Input:
string bar 100 -q

// Result:
$table->string('foo', 100)->unique();
```

Adding a _baz_ enum field, with 'user' and 'admin' as values, and 'user' as default value:

```
// Input:
enum baz user admin -d user

// Result:
$table->enum('baz', ['user', 'admin'])->default('user');
```

</p></details> 

## Usage

Each CRUD theme is registred as a dedicated command.

The only mandatory argument is the 

```
Usage:
  crud:classic [options] [--] <model>

Arguments:
  model                              The name of the Model.

Options:
  -p, --plurals[=PLURALS]            The plurals versions of the Model\'s names.
  -t, --timestamps[=TIMESTAMPS]      Add timestamps directives: [timestamps]|timestampsTz|nullableTimestamps|none
  -s, --soft-deletes[=SOFT-DELETES]  Add soft delete directives: [softDeletes]|softDeletesTz|none
  -c, --content[=CONTENT]            The list of Model\'s fields (signature syntax). (multiple values allowed)
  -o, --only[=ONLY]                  Generate only selected files: migration-class|model-class|factory-file|seeds-class|request-class|resource-class|controller-class|index-view|create-view|edit-view|show-view (multiple values allowed)
  -l, --layout[=LAYOUT]              The layout to extend into generated views: [crud-classic::layout]
  -h, --help                         Display this help message
  -q, --quiet                        Do not output any message
  -V, --version                      Display this application version
      --ansi                         Force ANSI output
      --no-ansi                      Disable ANSI output
  -n, --no-interaction               Do not ask any interactive question
      --env[=ENV]                    The environment the command should run under
  -v|vv|vvv, --verbose               Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Generate a default CRUD : migration, model, factory, seeder, request, resource, controller, views, routes
```
