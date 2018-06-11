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
    'theme' => 'crud-default',
    
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
    | string      : Models will be stored into /app/[ProvidedValue]
    |
    */
    'models-directory' => true,
    
    /*
    |---------------------------------------------------------------------------
    | Tidy configuration
    |---------------------------------------------------------------------------
    |
    | This is the configuration used with Tidy PHP extension when generating HTML files.
    |
    */
    'tidy' => [
        'indent' => true,
        'indent-spaces' => 4,
        'show-warnings' => false,
        'wrap' => 0
    ],
];
