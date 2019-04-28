<?php

namespace Bgaze\Crud\Themes\Classic;

use Bgaze\Crud\Themes\Api\Crud as Base;
use Bgaze\Crud\Themes\Classic\Builders;
use Bgaze\Crud\Themes\Classic\Compilers;

/**
 * The core class of the CRUD theme
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Crud extends Base {

    /**
     * The unique name of the CRUD theme.
     * 
     * @return string
     */
    static public function name() {
        return 'crud:classic';
    }

    /**
     * The description the CRUD theme.
     * 
     * @return string
     */
    static public function description() {
        return 'Generate a classic CRUD files: <fg=cyan>migration, model, factory, seeder, request, resource, controller, views, routes</>';
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
            'views.index' => __DIR__ . '/Stubs/index-view.stub',
            'views.show' => __DIR__ . '/Stubs/show-view.stub',
            'views.create' => __DIR__ . '/Stubs/create-view.stub',
            'views.edit' => __DIR__ . '/Stubs/edit-view.stub',
            'controller' => __DIR__ . '/Stubs/controller.stub',
            'routes' => __DIR__ . '/Stubs/routes.stub',
        ]);
    }

    /**
     * The builders availables in the CRUD theme.
     * 
     * @return array Name as key, full class name as value.
     */
    static public function builders() {
        $builders = parent::builders();

        // Remove current route registration builder because 
        // we want it to be the last builder
        unset($builders['routes-registration']);

        return array_merge($builders, [
            'index-view' => Builders\IndexView::class,
            'create-view' => Builders\CreateView::class,
            'edit-view' => Builders\EditView::class,
            'show-view' => Builders\ShowView::class,
            'routes-registration' => Builders\RoutesRegistration::class,
        ]);
    }

    /**
     * The compilers availables in the CRUD theme.
     * 
     * @return array Name as key, full class name as value.
     */
    static public function compilers() {
        return array_merge(parent::compilers(), [
            'print-content' => Compilers\PrintContent::class,
            'form-content' => Compilers\FormContent::class,
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

}
