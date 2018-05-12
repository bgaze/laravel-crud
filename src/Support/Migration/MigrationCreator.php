<?php

namespace Bgaze\Crud\Support\Migration;

use Illuminate\Database\Migrations\MigrationCreator as Base;

class MigrationCreator extends Base {

    use \Bgaze\Crud\Support\CrudHelpersTrait;

    /**
     * Create a new migration at the given path.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  string  $table
     * @param  array   $content
     * @return string
     * @throws \Exception
     */
    public function create($name, $path, $table = null, $create = true, array $content = []) {
        $this->ensureMigrationDoesntAlreadyExist($name);

        $stub = $this->populateStub($name, $this->getStub($table, $create), $table, $content);

        $path = $this->getPath($name, $path);

        $this->files->put($path, $stub);

        $this->firePostCreateHooks();

        return $path;
    }

    /**
     * Get the migration stub file.
     *
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    protected function getStub($table, $create) {
        return $this->files->get(config('crud.stubs.migration'));
    }

    /**
     * Populate the place-holders in the migration stub.
     *
     * @param  string  $name
     * @param  string  $stub
     * @param  string  $table
     * @param  array   $content
     * @return string
     */
    protected function populateStub($name, $stub, $table, array $content = []) {
        $stub = str_replace('DummyClass', $this->getClassName($name), $stub);
        $stub = str_replace('DummyTable', $table, $stub);
        $stub = str_replace('#CONTENT', implode("\n", $content), $stub);
        return $stub;
    }

}
