<?php

namespace Bgaze\Crud\Console\Crud;

use Illuminate\Foundation\Console\ResourceMakeCommand as Base;

class ViewsMakeCommand extends Base {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bgaze:crud:views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD views';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource collection';

}
