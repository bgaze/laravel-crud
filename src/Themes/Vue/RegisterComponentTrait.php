<?php

namespace Bgaze\Crud\Themes\Vue;

/**
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
trait RegisterComponentTrait {

    protected function registerComponent($name, $fullname, $path = '') {
        $stub = $this->stub('partials.register-component');
        $this
                ->replace($stub, 'ComponentFullName', $fullname)
                ->replace($stub, 'ComponentName', $name)
                ->replace($stub, 'ComponentPath', $path)
        ;

        $file = resource_path('assets/js/app.js');

        if ($this->files->exists($file)) {
            $content = str_replace('//CRUD//', $stub, $this->files->get($file));
            $this->files->put($file, $content);
        } else {
            $this->files->put($file, $stub);
        }
    }

}
