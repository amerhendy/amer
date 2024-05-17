<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel;

use Illuminate\Support\Facades\Facade;

/**
 * This object allows developers to use Amer::addField() instead of $this->Amer->addField(),
 * by providing a Facade that leads to the AmerPanel object. That object is stored in Laravel's
 * service container as 'Amer'.
 */
/**
 * @codeCoverageIgnore
 * Class AmerPanelFacade.
 *
 * @method static AmerPanel setModel($model)
 * @method static AmerPanel setRoute(string $route)
 * @method static AmerPanel setEntityNameStrings(string $singular, string $plural)
 * @method static AmerField field(string $name)
 * @method static AmerPanel addField(array $field)
 * @method static AmerPanel addFields(array $fields)
 * @method static AmerColumn column(string $name)
 * @method static AmerPanel addColumn(array $column)
 * @method static AmerPanel addColumns(array $columns)
 * @method static AmerPanel afterColumn(string $targetColumn)
 * @method static AmerPanel setValidation($class)
 *
 * @mixin AmerPanel
 */
class AmerPanelFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Amer';
    }
}
