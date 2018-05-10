<?php

namespace Bgaze\Crud\Support;

use Illuminate\Database\Migrations\MigrationCreator as Base;

class MigrationCreator extends Base {

    /**
     * Create a new migration at the given path.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  string  $table
     * @param  array   $fields
     * @return string
     * @throws \Exception
     */
    public function create($name, $path, $table = null, $create = true, array $fields = []) {
        $this->ensureMigrationDoesntAlreadyExist($name);

        $stub = $this->populateStub($name, $this->getStub($table, $create), $table, $fields);

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
        return $this->files->get($this->stubPath() . "/migration.stub");
    }

    /**
     * Populate the place-holders in the migration stub.
     *
     * @param  string  $name
     * @param  string  $stub
     * @param  string  $table
     * @param  array   $fields
     * @return string
     */
    protected function populateStub($name, $stub, $table, array $fields = []) {
        $stub = str_replace('DummyClass', $this->getClassName($name), $stub);
        $stub = str_replace('DummyTable', $table, $stub);

        if (empty($fields)) {
            $stub = str_replace("            #DUMMY_CONTENT\n", '', $stub);
        } else {
            $stub = str_replace('#DUMMY_CONTENT', implode("\n            ", $fields), $stub);
        }

        return $stub;
    }

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function stubPath() {
        return __DIR__ . '/../resources/stubs';
    }

}
