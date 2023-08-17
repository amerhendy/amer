<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;
use Illuminate\Support\Facades\Route;
trait TrashOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $segment  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current AmerController.
     */
    protected function setupTrashRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/trash', [
            'as'        => $routeName.'.trash',
            'uses'      => $controller.'@trash',
            'operation' => 'trash',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupTrashDefaults()
    {
        $this->Amer->allowAccess('trash');

        $this->Amer->operation('trash', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();
        });

        $this->Amer->operation(['list', 'show'], function () {
            $this->Amer->addButton('line', 'trash', 'view', listview('buttons.trash'), 'end');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return string
     */
    public function trash($id)
    {
        $this->Amer->hasAccessOrFail('trash');
        $id = $this->Amer->getCurrentEntryId() ?? $id;
        return $this->Amer->trash($id);
    }
}
