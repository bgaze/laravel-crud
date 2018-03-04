<?php

namespace Bgaze\Crud\Console\Crud;

use Illuminate\Foundation\Console\ResourceMakeCommand as Base;

class ThemeMakeCommand extends Base {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bgaze:crud:theme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD theme skeletton';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource collection';

}
