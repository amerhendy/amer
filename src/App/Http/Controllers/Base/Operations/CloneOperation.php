<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;
use Illuminate\Support\Facades\Route;
trait CloneOperation
{
    protected function setupCloneRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/clone', [
            'as'        => $routeName.'.clone',
            'uses'      => $controller.'@clone',
            'operation' => 'clone',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCloneDefaults()
    {
        $this->Amer->allowAccess('clone');

        $this->Amer->operation('clone', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();
        });

        $this->Amer->operation(['list', 'show'], function () {
            $req=$this->Amer->getRequest();
            if($req->bearerToken() == ""){
                $this->Amer->addButton('line', 'clone', 'view', listview('buttons.clone'), 'end');
            }else{
                $this->Amer->addButton('line', 'clone', 'view', 'buttons.clone', 'end');
            }

        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return string
     */
    public function clone($id)
    {
        $this->Amer->hasAccessOrFail('clone');
        $id = $this->Amer->getCurrentEntryId() ?? $id;
        return $this->Amer->Clon($id);
        $clonedEntry = $this->Amer->model->findOrFail($id)->replicate();
        return (string) $clonedEntry->push();
    }
}
