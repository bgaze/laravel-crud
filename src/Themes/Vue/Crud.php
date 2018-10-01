<?php

namespace Bgaze\Crud\Themes\Vue;

use Bgaze\Crud\Themes\Api\Crud as Base;
use Bgaze\Crud\Themes\Vue\Builders;

/**
 * The core class of the CRUD theme
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Crud extends Base {

    /**
     * A collection to store import directives for generated components
     *
     * @var \Illuminate\Support\Collection 
     */
    public $imports;

    /**
     * A collection to store route directive for generated components
     *
     * @var \Illuminate\Support\Collection 
     */
    public $routes;

    /**
     * The constructor of the class.
     *
     * @return void
     */
    public function __construct($model) {
        $this->imports = collect();
        $this->routes = collect();
        parent::__construct($model);
    }

    /**
     * The unique name of the CRUD theme.
     * 
     * @return string
     */
    static public function name() {
        return 'default:vue';
    }

    /**
     * The stubs availables in the CRUD theme.
     * 
     * @return array Name as key, absolute path as value.
     */
    static public function stubs() {
        return array_merge(parent::stubs(), [
            'partials.index-head' => __DIR__ . '/Stubs/partials/index-head.stub',
            'partials.index-body' => __DIR__ . '/Stubs/partials/index-body.stub',
            'partials.show-group' => __DIR__ . '/Stubs/partials/show-group.stub',
            'partials.form-group' => __DIR__ . '/Stubs/partials/form-group.stub',
            'partials.component-import' => __DIR__ . '/Stubs/partials/component-import.stub',
            'partials.component-route' => __DIR__ . '/Stubs/partials/component-route.stub',
            'components.index' => __DIR__ . '/Stubs/components/index.stub',
            'components.show' => __DIR__ . '/Stubs/components/show.stub',
            'components.create' => __DIR__ . '/Stubs/components/create.stub',
            'components.edit' => __DIR__ . '/Stubs/components/edit.stub',
            'register-components' => __DIR__ . '/Stubs/register-components.stub',
        ]);
    }

    /**
     * The builders availables in the CRUD theme.
     * 
     * @return array Name as key, full class name as value.
     */
    static public function builders() {
        return array_merge(parent::builders(), [
            'index-component' => Builders\IndexComponent::class,
            'create-component' => Builders\CreateComponent::class,
            'edit-component' => Builders\EditComponent::class,
            'show-component' => Builders\ShowComponent::class,
        ]);
    }

    /**
     * Get CRUD index url to display in successfull generation message.
     * 
     * Cannot be generated with route() helper as created routes are not
     * loaded in current process.
     * 
     * @return string
     */
    public function indexPath() {
        return url($this->getPluralsKebabSlash());
    }

    /**
     * Remember component import and route.
     * 
     * @param string $file      The basename of the component file
     * @param string $path      The path to append to CRUD base url
     */
    public function registerComponent($file, $path = '') {
        $stubs = self::stubs();
        $replacements = [
            'ComponentName' => $this->getModelFullStudly() . $file,
            'ComponentRoute' => $this->getPluralsKebabDot() . '.' . strtolower($file),
            'ComponentFile' => $this->getPluralsKebabSlash() . '/' . $file,
            'ComponentPath' => $this->getPluralsKebabSlash() . $path
        ];

        $stub = file_get_contents($stubs['partials.component-import']);
        $stub = str_replace(array_keys($replacements), array_values($replacements), $stub);
        $this->imports->push($stub);

        $stub = file_get_contents($stubs['partials.component-route']);
        $stub = str_replace(array_keys($replacements), array_values($replacements), $stub);
        $this->routes->push($stub);
    }

    /**
     * Register CRUD components after a successfull generation.
     * 
     * @param array $arguments  The arguments passed to the command
     * @param array $options    The options passed to the command
     * @return null|string      A message to display in the command output
     */
    public function onSuccessfullBuild(array $arguments, array $options) {
        if ($this->imports->isEmpty() || $this->routes->isEmpty()) {
            return null;
        }

        $stubs = self::stubs();
        $stub = file_get_contents($stubs['register-components']);

        $stub = str_replace('ModelFullName', $this->getModelFullName(), $stub);
        $stub = str_replace('#IMPORTS', $this->imports->implode("\n"), $stub);
        $stub = str_replace('#ROUTES', $this->routes->implode(",\n"), $stub);

        $file = resource_path('assets/js/app.js');
        if (!file_exists($file)) {
            file_put_contents($file, $stub);
        } else {
            $content = file_get_contents($file);
            if (preg_match('/\/\/CRUD\/\//', $content)) {
                file_put_contents($file, str_replace('//CRUD//', $stub, $content));
            } else {
                file_put_contents($file, $content, FILE_APPEND);
            }
        }

        return ' <info>Components imported into:</info> /resources/assets/js/app.js';
    }

}
