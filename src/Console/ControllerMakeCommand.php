<?php

namespace Bgaze\Crud\Console;

use Illuminate\Routing\Console\ControllerMakeCommand as Base;

class ControllerMakeCommand extends Base {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bgaze:crud:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD controller class';

}
