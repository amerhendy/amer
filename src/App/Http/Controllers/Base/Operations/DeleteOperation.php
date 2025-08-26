<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;

use Illuminate\Support\Facades\Route;

trait DeleteOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $segment  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current AmerController.
     */
    protected function setupDeleteRoutes($segment, $routeName, $controller)
    {
        Route::delete($segment.'/{id}', [
            'as'        => $routeName.'.destroy',
            'uses'      => $controller.'@destroy',
            'operation' => 'delete',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupDeleteDefaults()
    {
        $this->Amer->allowAccess('delete');

        $this->Amer->operation('delete', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();
        });

        $this->Amer->operation(['list', 'show'], function () {
            $req=$this->Amer->getRequest();
            if($req->bearerToken() == ""){
                $this->Amer->addButton('line', 'delete', 'view', listview('buttons.delete'),'end');
            }else{
                $this->Amer->addButton('line', 'delete', 'view', 'buttons.delete');
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return string
     */
    public function destroy($id)
    {
        return $id;
        $this->Amer->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->Amer->getCurrentEntryId() ?? $id;

        return $this->Amer->delete($id);
    }
}
