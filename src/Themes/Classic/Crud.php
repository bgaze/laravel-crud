<?php


namespace Bgaze\Crud\Themes\Classic;

use Bgaze\Crud\Themes\Api\Crud as BaseCrud;
use Illuminate\Support\Collection;

/**
 * Class Crud
 *
 * @property string $ViewsLayout  Example: crud-classic::layout
 * @property string $RoutesAlias  Example: my-grand-parents.my-parents
 */
class Crud extends BaseCrud
{
    /**
     * Set the plural version of Model's parents and name.
     *
     * @param  Collection  $plurals
     *
     * @return  Crud
     */
    public function setPlurals(Collection $plurals)
    {
        parent::setPlurals($plurals);

        $this->addVariable('RoutesAlias', $this->PluralsParentsKebabDot);

        return $this;
    }

    /**
     * Set the layout to extend in generated views.
     *
     * @param  boolean|string  $layout  The layout to extend in generated views.
     *
     * @return  $this
     */
    public function setLayout($layout)
    {
        parent::setLayout($layout);

        $this->addVariable('ViewsLayout', $this->layout);

        return $this;
    }
}