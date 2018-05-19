<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Bgaze\Crud\Support\CrudHelpersTrait;

class ModelMakeCommand extends Command {

    use CrudHelpersTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:model {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--theme= : The theme to use to generate CRUD.}
        {--t|timestamps : Add timestamps directives}
        {--s|soft-delete : Add soft delete directives}
        {--f|fillables=* : The list of Model\'s fillable fields}
        {--d|dates=* : The list of Model\'s date fields}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD Eloquent model class';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle() {
        // Initialize CRUD theme.
        $theme = $this->laravel->make($this->option('theme') ?: config('crud-config.theme'), [
            'model' => $this->argument('model'),
            'plural' => $this->option('plural')
        ]);

        // Write model file.
        $path = $theme->generatePhpFile('model', $theme->getModelPath(), function($theme, $stub) {
            $theme
                    ->replace($stub, 'ModelNamespace')
                    ->replace($stub, 'ModeleStudly')
                    ->replace($stub, 'PluralSnake')
                    ->replace($stub, '#TIMESTAMPS', $this->option('timestamps') ? 'public $timestamps = true;' : '')
                    ->replace($stub, '#SOFTDELETE', $this->option('soft-delete') ? 'use Illuminate\Database\Eloquent\SoftDeletes;' : '')
                    ->replace($stub, '#FILLABLES', $this->getFillables())
                    ->replace($stub, '#DATES', $this->getDates())
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Model created : <fg=white>$path</>");
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getFillables() {
        $fillables = collect($this->option('fillables'))
                ->map(function($v) {
                    $v = trim($v);
                    return empty($v) ? null : $this->compileValueForPhp($v);
                })
                ->filter()
                ->implode(', ');

        return "protected \$fillable = [{$fillables}];";
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getDates() {
        $dates = collect($this->option('dates'));

        if ($this->option('soft-delete') && !$dates->contains('deleted_at')) {
            $dates->prepend('deleted_at');
        }

        $dates = $dates->map(function($v) {
                    $v = trim($v);
                    return empty($v) ? null : $this->compileValueForPhp($v);
                })
                ->filter()
                ->implode(', ');

        return "protected \$dates = [{$dates}];";
    }

}
