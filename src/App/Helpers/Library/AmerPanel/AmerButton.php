<?php

namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel;

use Amerhendy\Amer\ViewNamespaces;
use Illuminate\Support\Traits\Conditionable;

class AmerButton
{
    use Conditionable;

    public $stack;
    public $name;
    public $type;
    public $content;
    public $position;

    public function __construct($name, $stack = null, $type = null, $content = null, $position = null)
    {
        if (is_array($name)) {
            extract($name);
        }
        $this->name = $name ?? 'button_'.rand(1, 999999999);
        $this->stack = $stack ?? 'top';
        $this->type = $type ?? 'view';
        $this->content = $content;
        $this->position = $position ?? ($this->stack == 'line' ? 'beginning' : 'end');
        return $this->save();
    }
    public static function name($attributes = null)
    {
        return new static($attributes);
    }
    public static function add($attributes = null)
    {
        return new static($attributes);
    }
    public static function make($attributes = null)
    {
        $button = static::add($attributes);
        $button->stack('hidden');

        return $button;
    }
    public function stack($stack)
    {
        $this->stack = $stack;

        return $this->save();
    }
    public function type($type)
    {
        $this->type = $type;

        return $this->save();
    }

    public function content($content)
    {
        $this->content = $content;

        return $this->save();
    }
    public function view($value)
    {
        $this->content = $value;
        $this->type = 'view';

        return $this->save();
    }

    /**
     * Sets the name of the method on the model that contains the HTML for this button.
     * Sets the button type as 'model_function'.
     *
     * @param  string  $value  Name of the method on the model.
     * @return AmerButton
     */
    public function modelFunction($value)
    {
        $this->content = $value;
        $this->type = 'model_function';
        $this->stack = 'line';

        return $this->save();
    }

    /**
     * Sets the name of the method on the model that contains the HTML for this button.
     * Sets the button type as 'model_function'.
     * Alias of the modelFunction() method.
     *
     * @param  string  $value  Name of the method on the model.
     * @return AmerButton
     */
    public function model_function($value)
    {
        return $this->modelFunction($value);
    }

    /**
     * Unserts an property that is set on the current button.
     * Possible properties: name, stack, type, content.
     *
     * @param  string  $property  Name of the property that should be cleared.
     * @return AmerButton
     */
    public function forget($property)
    {
        $this->{$property} = null;

        return $this->save();
    }

    // --------------
    // SETTER ALIASES
    // --------------

    /**
     * Moves the button to a certain button stack.
     * Alias of stack().
     *
     * @param  string  $stack  The name of the stack where the button should be moved.
     * @return self
     */
    public function to($stack)
    {
        return $this->stack($stack);
    }

    /**
     * Moves the button to a certain button stack.
     * Alias of stack().
     *
     * @param  string  $stack  The name of the stack where the button should be moved.
     * @return self
     */
    public function group($stack)
    {
        return $this->stack($stack);
    }

    /**
     * Moves the button to a certain button stack.
     * Alias of stack().
     *
     * @param  string  $stack  The name of the stack where the button should be moved.
     * @return self
     */
    public function section($stack)
    {
        return $this->stack($stack);
    }

    // -------
    // GETTERS
    // -------

    /**
     * Get the end result that should be displayed to the user.
     * The HTML itself of the button.
     *
     * @param  object|null  $entry  The eloquent Model for the current entry or null if no current entry.
     * @return HTML
     */
    public function getHtml($entry = null)
    {
        $button = $this;
        $Amer = $this->Amer();
        if ($this->type == 'model_function') {
            if (is_null($entry)) {
                return $Amer->model->{$button->content}($Amer);
            }
            return $entry->{$button->content}($Amer);
        }
        if ($this->type == 'view') {
            //dd($button->getFinalViewPath(),$entry);
            return view($button->getFinalViewPath(), compact('button', 'Amer', 'entry'));
        }

        abort(500, 'Unknown button type');
    }
    private function getViewPathsWithFallbacks()
    {
        $type = $this->name;
        $paths = array_map(function ($item) use ($type) {
            return $item.'.'.$type;
        }, ViewNamespaces::getFor('buttons'));

        return array_merge([$this->content], $paths);
    }

    private function getFinalViewPath()
    {
        foreach ($this->getViewPathsWithFallbacks() as $path) {
            if (view()->exists($path)) {
                return $path;
            }
        }
        abort(500, 'Button view and fallbacks do not exist for '.$this->name.' button.');
    }

    /**
     * Get the key for this button in the global buttons collection.
     *
     * @return int
     */
    public function getKey()
    {
        return $this->Amer()->getButtonKey($this->name);
    }

    // -----
    // ORDER
    // -----
    // Manipulate the button collection (inside the global AmerPanel object).

    /**
     * Move this button to be the first in the buttons list.
     *
     * @return AmerButton
     */
    public function makeFirst()
    {
        $this->remove();
        $this->collection()->prepend($this);

        return $this;
    }

    /**
     * Move this button to be the last one in the buttons list.
     *
     * @return AmerButton
     */
    public function makeLast()
    {
        $this->remove();
        $this->collection()->push($this);

        return $this;
    }

    /**
     * Move the current filter after another filter.
     *
     * @param  string  $destination  Name of the destination filter.
     * @return AmerFilter
     */
    public function after($destination)
    {
        $this->Amer()->moveButton($this->name, 'after', $destination);

        return $this;
    }

    /**
     * Move the current field before another field.
     *
     * @param  string  $destination  Name of the destination field.
     * @return AmerFilter
     */
    public function before($destination)
    {
        $this->Amer()->moveButton($this->name, 'before', $destination);

        return $this;
    }

    /**
     * Remove the button from the global button collection.
     *
     * @return AmerButton
     */
    public function remove()
    {
        $this->collection()->pull($this->getKey());

        return $this;
    }

    // --------------
    // GLOBAL OBJECTS
    // --------------
    // Access to the objects stored in Laravel's service container.

    /**
     * Access the global collection when all buttons are stored.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->Amer()->buttons();
    }

    /**
     * Access the global AmerPanel object.
     *
     * @return \Library\AmerPanel\AmerPanel
     */
    public function Amer()
    {
        return app('Amer');
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
     * @return AmerButton
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
     * @return AmerButton
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
     * Update the global AmerPanel object with the current button.
     *
     * @return AmerButton
     */
    private function save()
    {
        $itemExists = $this->collection()->contains('name', $this->name);
        if (! $itemExists) {
            if ($this->position == 'beginning') {
                $this->collection()->prepend($this);
            } else {
                $this->collection()->push($this);
            }

            // clear the custom position, so that the next daisy chained method
            // doesn't move it yet again
            $this->position = null;
        } else {
            $this->collection()->replace([$this->getKey() => $this]);
        }

        return $this;
    }
}
