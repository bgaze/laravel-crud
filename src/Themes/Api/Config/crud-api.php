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
        'seeds' => true,
        'request' => true,
        'resource' => true,
        'controller' => true,
        'routes' => true,
    ],

];