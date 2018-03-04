<?php

namespace Bgaze\Crud\Console\Crud;

use Illuminate\Foundation\Console\ModelMakeCommand as Base;

class ModelMakeCommand extends Base {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bgaze:crud:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD Eloquent model class';

}
