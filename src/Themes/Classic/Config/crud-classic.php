<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Default layout to extend in generated views
    |---------------------------------------------------------------------------
    |
    | The layout to extend into generated views.
    | This setting can be overrided using --layout option when invoking the generator command.
    |
    | Leave empty to use theme's default layout: crud-classic::layout
    |
    */
    'layout' => null,

    /*
    |---------------------------------------------------------------------------
    | Default theme's tasks to execute when building a CRUD
    |---------------------------------------------------------------------------
    |
    | Set to false to disable a task by default.
    | This setting can be overrided using --only option when invoking the generator command.
    |
    */
    'tasks' => [
        'migration' => true,
        'model' => true,
        'factory' => true,
        'seeds' => true,
        'request' => true,
        'resource' => false,
        'controller' => true,
        'index-view' => true,
        'create-view' => true,
        'edit-view' => true,
        'show-view' => true,
        'routes' => true,
    ],

    /*
    |---------------------------------------------------------------------------
    | Expand routes
    |---------------------------------------------------------------------------
    |
    | Use expanded syntax instead of resource syntax when registering routes.
    |
    | Resource syntax example:
    |
    |   Route::resource('articles', 'ArticleController');
    |
    | Expanded syntax example:
    |
    |   Route::prefix('/articles')->name('articles.')->group(function () {
    |       Route::get('/', 'ArticleController@index')->name('index');
    |       Route::get('/create', 'ArticleController@create')->name('create');
    |       Route::post('/', 'ArticleController@store')->name('store');
    |       Route::get('/{article}', 'ArticleController@show')->name('show');
    |       Route::get('/{article}/edit', 'ArticleController@edit')->name('edit');
    |       Route::put('/{article}', 'ArticleController@update')->name('update');
    |       Route::delete('/{article}', 'ArticleController@destroy')->name('destroy');
    |   });
    |
    */
    'expand-routes' => false,

];