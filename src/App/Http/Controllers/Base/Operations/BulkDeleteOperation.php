<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;
use Illuminate\Support\Facades\Route;
trait BulkDeleteOperation
{
    protected function setupBulkDeleteRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/bulk-delete', [
            'as'        => $routeName.'.Bulkdelete',
            'uses'      => $controller.'@Bulkdelete',
            'operation' => 'delete',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBulkDeleteDefaults()
    {
        $this->Amer->allowAccess('BulkDelete');

        $this->Amer->operation('Bulkdelete', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();
        });

        $this->Amer->operation(['list', 'show'], function () {
            $req=$this->Amer->getRequest();
            if($req->bearerToken() == ""){
                $this->Amer->addButton('bottom', 'bulk_delete', 'view', listview('buttons.bulk_delete'), 'beginning');
            }else{
                $this->Amer->addButton('bottom', 'bulk_delete', 'view','buttons.bulk_delete', 'beginning');
            }
        });
    }
    public function Bulkdelete()
    {
        $this->Amer->hasAccessOrFail('BulkDelete');
        $entries = request()->input('entries', []);
        $DeletedEnteries = [];
        foreach ($entries as $key => $id) {
            $DeletedEnteries[$id]=$this->Amer->delete($id);
        }
        return $DeletedEnteries;
    }
}
