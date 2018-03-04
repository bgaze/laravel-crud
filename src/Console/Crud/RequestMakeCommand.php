<?php

namespace Bgaze\Crud\Console\Crud;

use Illuminate\Foundation\Console\RequestMakeCommand as Base;

class RequestMakeCommand extends Base {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bgaze:crud:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD form request class';

}
