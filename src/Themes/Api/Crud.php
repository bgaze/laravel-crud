<?php

namespace Bgaze\Crud\Themes\Api;

use Bgaze\Crud\Core\Crud as Base;
use Bgaze\Crud\Themes\Api\Builders;

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
        return 'default:api';
    }

    /**
     * The stubs availables in the CRUD theme.
     * 
     * @return array Name as key, absolute path as value.
     */
    static public function stubs() {
        return [
            'migration' => __DIR__ . '/Stubs/migration.stub',
            'model' => __DIR__ . '/Stubs/model.stub',
            'factory' => __DIR__ . '/Stubs/factory.stub',
            'seeds' => __DIR__ . '/Stubs/seeds.stub',
            'request' => __DIR__ . '/Stubs/request.stub',
            'resource' => __DIR__ . '/Stubs/resource.stub',
            'controller' => __DIR__ . '/Stubs/controller.stub',
            'routes' => __DIR__ . '/Stubs/routes.stub',
        ];
    }

    /**
     * The builders availables in the CRUD theme.
     * 
     * @return array Name as key, full class name as value.
     */
    static public function builders() {
        return [
            'migration-class' => Builders\Migration::class,
            'model-class' => Builders\Model::class,
            'factory-file' => Builders\Factory::class,
            'seeds-class' => Builders\Seeds::class,
            'request-class' => Builders\Request::class,
            'resource-class' => Builders\Resource::class,
            'controller-class' => Builders\Controller::class,
        ];
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
        return url('api/' . $this->getPluralsKebabSlash());
    }

}
