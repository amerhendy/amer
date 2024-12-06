<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;
use Illuminate\Support\Facades\Route;
trait InlineCreateOperation
{
    protected function setupInlineCreateRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/inline/create/modal', [
            'as'        => $segment.'-inline-create',
            'uses'      => $controller.'@getInlineCreateModal',
            'operation' => 'InlineCreate',
        ]);
        Route::post($segment.'/inline/create', [
            'as'        => $segment.'-inline-create-save',
            'uses'      => $controller.'@storeInlineCreate',
            'operation' => 'InlineCreate',
        ]);
    }
    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupInlineCreateDefaults()
    {
        if (method_exists($this, 'setup')) {
            $this->setup();
        }
        if (method_exists($this, 'setupCreateOperation')) {
            $this->setupCreateOperation();
        }

        $this->Amer->applyConfigurationFromSettings('create');
    }
    public function getInlineCreateModal(){
        if (! request()->has('entity')) {
            abort(400, 'No "entity" inside the request.');
        }
        return view(
            fieldview('relationship.inline_create_modal'),
            [
                'fields' => $this->Amer->getCreateFields(),
                'action' => 'create',
                'Amer' => $this->Amer,
                'entity' => request()->get('entity'),
                'modalClass' => request()->get('modal_class'),
                'parentLoadedFields' => request()->get('parent_loaded_fields'),
            ]
        );
    }
    public function storeInlineCreate(){
        $request=Request();
        $fd=$request->toArray();
        $result = $this->store();
        \Alert::flush();
        return $result;
    }
}
