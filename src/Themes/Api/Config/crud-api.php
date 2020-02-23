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
    |   Route::prefix('/articles')->group(function () {
    |       Route::get('/', 'ArticleController@index')->name('api.articles.index');
    |       Route::post('/', 'ArticleController@store')->name('api.articles.store');
    |       Route::get('/{article}', 'ArticleController@show')->name('api.articles.show');
    |       Route::put('/{article}', 'ArticleController@update')->name('api.articles.update');
    |       Route::delete('/{article}', 'ArticleController@destroy')->name('api.articles.destroy');
    |   });
    |
    */
    'expand-routes' => false,

];