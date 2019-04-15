> Documentation in progress ;-)

# Laravel CRUD Generator

<p align="center">
  <img src="doc/assets/demo.png">
</p>

## Overview

This package allows to generate CRUDs in a breath for your Laravel 5.5+ applications.

Using the SignedInput syntax, it offers a concise and handy way to define the model fields.

It is designed to be easily extended in order to create custom CRUD (aka _themes_).  
Each theme is available as a dedicated console command.

Two themes are provided :

* **crud:classic** generates a fully fonctionnal "classic" CRUD, creating for you : migration, model, factory, seeder, request, resource, controller, views and routes.
* **crud:api:** generates a fully fonctionnal REST API CRUD, creating for you : migration, model, factory, seeder, request, resource, controller and routes.

## Demo

[This short video on Vimeo](https://vimeo.com/330304646) shows the creation of a CRUD for an **Article** model containing following fields:

* **category:** indexed enum field, with _foo_ and _bar_ as values.
* **title:** a mandatory string field.
* **body:** a nullable text field.
* **active:** a boolean field with _0_ as default value.
* Timestamps and softDeletes fields.

## Why this package?

Laravel is my favorite PHP framework.  
Using it daily, at work and for my private project, I've noticed that each time I create a model, 
I have to do the same repetitive tasks before starting to really work on the application itself:

1. Generate classes: model, migration, controller, request, factory, seeder, ...
2. Define the table fields into migration.
3. Create the rules into the request class.
4. Create the model faker into factory.
5. Define CRUD actions into controller.
6. Register controller routes.
7. Create CRUD views and model forms.

Sticking to framework conventions, I believe that this process can be automated a lot to produce a generic functionnal CRUD.  
So we could customize it, keeping the focus on the application logic.

The key for that are to define the Model table field, from whom a lot of things can be deducted.  
For instance request rules (a non-nullable field is required) or form fields (an enum field is often a select)...

But even if the logic behind CRUD generation will be almost the same, the files to generate can vary a lot depending on the tools used in the app.  
For instance, using classic HTML or Vue.js, a CRUD files will be very different.

So this package goals are to provide:

* A handy way to define required informations for a complete CRUD generation.
* A robust and extensible base to create easily custom CRUD generators (named **themes**).
* Themes for API and classic HTML (using Blade templates) CRUDs. 

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

## Conventions

> This section explain very important concepts required to use the package.  
> Please read it carrefully.

As a convention, we designate by:

* **FullName:** the model's name including namespace without the `App` part. 
* **Plurals:** the pluralized form of each segment of the FullName.

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

## Usage

Each CRUD theme is registred as a dedicated command.  
Please call them with the `-h` switch to see a complete description of arguments of options. 

The only mandatory argument is the FullName of the model.  
When invoked, a wizard will drive you through following steps.

### Plurals definition

Plurals is automatically suggested based on model's FullName and english language.

Please pay attention to that important step and correct the proposed value if needed.  
Otherwise, simply confirm.

### Timestamps and softDeletes

The wizard 


### SignedInput

When dealing with CRUDs, a tricky part is often to define model's properties (aka table fields).

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

### No interraction.

Any step of the process can also be set using options.

<details><summary><b>Example</b></summary><p>

Creating a CRUD without interractions :

```
php artisan crud:classic Article -n \
-c "string title" \
-c "enum category foo bar foobar -i" \
-c "text body -n" \
-c "unsignedInteger views -d 0" \
-c "boolean active -d 1" \
&& php artisan migrate \
&& php artisan db:seed --class=ArticlesTableSeeder 
```

</p></details> 
