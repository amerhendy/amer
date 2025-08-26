<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;
use Illuminate\Support\Facades\Route;
trait BulkCloneOperation
{
    protected function setupBulkCloneRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/bulk-clone', [
            'as'        => $routeName.'.bulkClone',
            'uses'      => $controller.'@bulkClone',
            'operation' => 'clone',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBulkCloneDefaults()
    {
        $this->Amer->allowAccess('bulkClone');

        $this->Amer->operation('bulkClone', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();
        });

        $this->Amer->operation(['list', 'show'], function () {
            $req=$this->Amer->getRequest();
            if($req->bearerToken() == ""){
                $this->Amer->addButton('bottom', 'bulk_clone', 'view', listview('buttons.bulk_clone'), 'beginning');
            }else{
                $this->Amer->addButton('bottom', 'bulk_clone', 'view', 'buttons.bulk_clone', 'beginning');
            }

        });
    }
    public function bulkClone()
    {
        $this->Amer->hasAccessOrFail('bulkClone');
        $entries = request()->input('entries', []);
        $clonedEntries = [];
        foreach ($entries as $key => $id) {
            $clonedEntries[$id]=$this->Amer->Clon($id);
        }
        return $clonedEntries;
    }
}
