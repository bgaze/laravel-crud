<?php

return [
    /*
    |---------------------------------------------------------------------------
    | Default theme
    |---------------------------------------------------------------------------
    |
    | The theme to use when not specified in CRUD commands.
    |
    */
    'theme' => 'CrudDefault',
    
    /*
    |---------------------------------------------------------------------------
    | Default layout
    |---------------------------------------------------------------------------
    |
    | The layout to extend into generated views.
    | Spedified layout should extend default theme layout.
    | 
    | empty : use default theme layout.
    |
    */
    'layout' => null,
    
    /*
    |---------------------------------------------------------------------------
    | Models directory
    |---------------------------------------------------------------------------
    |
    | Store models in a subdirectory of /app.
    | This is usefull when dealing with numerous and/or nested models.
    |
    | empty|false : No subdirectory
    | true        : Models will be stored into /app/Models
    | string      : Models will be stored into /app/customValue
    |
    */
    'models-directory' => true,
];
