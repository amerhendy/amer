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
            $req=$this->Amer->getRequest();
            if($req->bearerToken() == ""){
                $this->Amer->addButton('line', 'trash', 'view', listview('buttons.trash'), 'end');
            }else{
                $this->Amer->addButton('line', 'trash', 'view', 'buttons.trash');
            }
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
        $data=$this->Amer->model::withTrashed()->find($id);
        if(!$data){
            return \AmerHelper::responseError(trans('AMER::errors.idNotFound'),422);
        }
        if(!$this->Amer->trash($id)){
                return \AmerHelper::responseError(trans('AMER::crud.delete_confirmation_not_deleted_title'),432);
        }
        return response()->noContent();
    }
}
