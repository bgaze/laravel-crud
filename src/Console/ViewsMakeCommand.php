<?php

namespace Bgaze\Crud\Console;

use Illuminate\Foundation\Console\ResourceMakeCommand as Base;

class ViewsMakeCommand extends Base {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:views';

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
