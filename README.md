# bgaze/laravel-crud

> Documentation in progress ;-)

This package allows to generate in a breath entire CRUD into your Laravel 5.5+ applications.

See it in action, creating a CRUD for an **Article** model containing:

* An indexed **category:** enum field, with _ffo_ and _bar_ as values.
* A mandatory string field **title**.
* A nullable **body** text field.
* A boolean **active** field with _0_ as default value.

<p align="center">
    
[![https://vimeo.com/330304646](doc/assets/demo.png)](https://vimeo.com/330304646)

</p>

## Overview

Using the command signature syntax, it offers a handy way to define the model fields.

It is designed to be easily extended in order to create custom CRUD (aka _themes_).  
Each theme is available as a dedicated console command.

Two themes are provided :

* **crud:classic** generates a fully fonctionnal "classic" CRUD, creating for you : migration, model, factory, seeder, request, resource, controller, views and routes.
* **crud:api:** generates a fully fonctionnal REST API CRUD, creating for you : migration, model, factory, seeder, request, resource, controller and routes.
