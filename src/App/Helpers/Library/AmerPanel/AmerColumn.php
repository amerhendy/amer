<?php

namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel;

use Illuminate\Support\Traits\Conditionable;

/**
 * Adds fluent syntax to Amer Columns.
 *
 * In addition to the existing:
 * - Amer::addColumn(['name' => 'price', 'type' => 'number']);
 *
 * Developers can also do:
 * - Amer::column('price')->type('number');
 *
 * And if the developer uses AmerColumn as Column in their AmerController:
 * - Column::name('price')->type('number');
 *
 * @method self type(string $value)
 * @method self label(string $value)
 * @method self searchLogic(mixed $value)
 * @method self orderLogic(callable $value)
 * @method self orderable(bool $value)
 * @method self wrapper(array $value)
 * @method self visibleInTable(bool $value)
 * @method self visibleInModal(bool $value)
 * @method self visibleInExport(bool $value)
 * @method self visibleInShow(bool $value)
 * @method self priority(int $value)
 * @method self key(string $value)
 */
class AmerColumn
{
    use Conditionable;

    protected $attributes;

    public function __construct($name)
    {
        $column = $this->Amer()->firstColumnWhere('name', $name);


        // if column exists
        if ((bool) $column) {
            // use all existing attributes
            $this->setAllAttributeValues($column);
        } else {
            // it means we're creating the column now,
            // so at the very least set the name attribute
            $this->setAttributeValue('name', $name);
        }

        // guess all attributes that weren't explicitly defined
        $this->attributes = $this->Amer()->makeSureColumnHasNeededAttributes($this->attributes);

        $this->save();
    }

    public function Amer()
    {
        return app()->make('Amer');
    }

    /**
     * Create a AmerColumn object with the parameter as its name.
     *
     * @param  string  $name  Name of the column in the db, or model attribute.
     * @return AmerColumn
     */
    public static function name($name)
    {
        return new static($name);
    }

    /**
     * Change the AmerColumn key.
     *
     * @param  string  $key  New key for the column
     * @return AmerColumn
     */
    public function key(string $key)
    {
        if (! isset($this->attributes['name'])) {
            abort(500, 'Column name must be defined before changing the key.');
        }

        $columns = $this->Amer()->columns();

        $searchKey = $this->attributes['key'];
        $column = $this->attributes;

        if (isset($columns[$searchKey])) {
            unset($columns[$searchKey]);
            $column['key'] = $key;
        }
        $this->attributes = $column;
        $this->Amer()->setOperationSetting('columns', array_merge($columns, [$key => $column]));

        return $this;
    }

    /**
     * Remove the current column from the current operation.
     *
     * @return void
     */
    public function remove()
    {
        $this->Amer()->removeColumn($this->attributes['name']);
    }

    /**
     * Remove an attribute from the column definition array.
     *
     * @param  string  $attribute  Name of the attribute being removed
     * @return AmerColumn
     */
    public function forget($attribute)
    {
        $this->Amer()->removeColumnAttribute($this->attributes['name'], $attribute);

        return $this;
    }

    /**
     * Move the current column after another column.
     *
     * @param  string  $destinationColumn  Name of the destination column.
     * @return AmerColumn
     */
    public function after($destinationColumn)
    {
        $this->Amer()->removeColumn($this->attributes['name']);
        $this->Amer()->addColumn($this->attributes)->afterColumn($destinationColumn);

        return $this;
    }

    /**
     * Move the current column before another column.
     *
     * @param  string  $destinationColumn  Name of the destination column.
     * @return AmerColumn
     */
    public function before($destinationColumn)
    {
        $this->Amer()->removeColumn($this->attributes['name']);
        $this->Amer()->addColumn($this->attributes)->beforeColumn($destinationColumn);

        return $this;
    }

    /**
     * Make the current column the first one in the columns list.
     *
     * @return AmerColumn
     */
    public function makeFirst()
    {
        $this->Amer()->removeColumn($this->attributes['name']);
        $this->Amer()->addColumn($this->attributes)->makeFirstColumn();

        return $this;
    }

    /**
     * Make the current column the last one in the columns list.
     *
     * @return AmerColumn
     */
    public function makeLast()
    {
        $this->Amer()->removeColumn($this->attributes['name']);
        $this->Amer()->addColumn($this->attributes);

        return $this;
    }

    // -----------------
    // DEBUGGING METHODS
    // -----------------

    /**
     * Dump the current object to the screen,
     * so that the developer can see its contents.
     *
     * @codeCoverageIgnore
     *
     * @return AmerColumn
     */
    public function dump()
    {
        dump($this);

        return $this;
    }

    /**
     * Dump and die. Duumps the current object to the screen,
     * so that the developer can see its contents, then stops
     * the execution.
     *
     * @codeCoverageIgnore
     *
     * @return AmerColumn
     */
    public function dd()
    {
        dd($this);

        return $this;
    }

    // ---------------
    // PRIVATE METHODS
    // ---------------

    /**
     * Set the value for a certain attribute on the AmerColumn object.
     *
     * @param  string  $attribute  Name of the attribute.
     * @param  mixed  $value  Value of that attribute.
     */
    private function setAttributeValue($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    /**
     * Replace all column attributes on the AmerColumn object
     * with the given array of attribute-value pairs.
     *
     * @param  array  $array  Array of attributes and their values.
     */
    private function setAllAttributeValues($array)
    {
        $this->attributes = $array;
    }

    /**
     * Update the global AmerPanel object with the current column attributes.
     *
     * @return AmerColumn
     */
    private function save()
    {
        $key = $this->attributes['key'] ?? $this->attributes['name'];

        if ($this->Amer()->hasColumnWhere('key', $key)) {
            $this->Amer()->setColumnDetails($key, $this->attributes);
        } else {
            $this->Amer()->addColumn($this->attributes);
        }

        return $this;
    }

    // -------------
    // MAGIC METHODS
    // -------------

    /**
     * If a developer calls a method that doesn't exist, assume they want:
     * - the AmerColumn object to have an attribute with that value;
     * - that column be updated inside the global AmerPanel object;.
     *
     * Eg: type('number') will set the "type" attribute to "number"
     *
     * @param  string  $method  The method being called that doesn't exist.
     * @param  array  $parameters  The arguments when that method was called.
     * @return AmerColumn
     */
    public function __call($method, $parameters)
    {
        $this->setAttributeValue($method, $parameters[0]);

        return $this->save();
    }
}
