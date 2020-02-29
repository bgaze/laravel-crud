<?php

return [

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
        'seeder' => true,
        'request' => true,
        'resource' => true,
        'controller' => true,
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
    |   Route::apiResource('articles', 'ArticleController', ['as' => 'api']);
    |
    | Expanded syntax example:
    |
    |   Route::prefix('/articles')->name('api.')->group(function () {
    |       Route::get('/', 'ArticleController@index')->name('index');
    |       Route::post('/', 'ArticleController@store')->name('store');
    |       Route::get('/{article}', 'ArticleController@show')->name('show');
    |       Route::put('/{article}', 'ArticleController@update')->name('update');
    |       Route::delete('/{article}', 'ArticleController@destroy')->name('destroy');
    |   });
    |
    */
    'expand-routes' => false,

];