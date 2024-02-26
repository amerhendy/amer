<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;

use Illuminate\Support\Facades\Route;

trait UpdateOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $name  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current AmerController.
     */
    protected function setupUpdateRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/edit', [
            'as'        => $routeName.'.edit',
            'uses'      => $controller.'@edit',
            'operation' => 'update',
        ]);

        Route::put($segment.'/{id}', [
            'as'        => $routeName.'.update',
            'uses'      => $controller.'@update',
            'operation' => 'update',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupUpdateDefaults()
    {
        $this->Amer->allowAccess('update');

        $this->Amer->operation('update', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();

            if ($this->Amer->getModel()->translationEnabled()) {
                $this->Amer->addField([
                    'name' => '_locale',
                    'type' => 'hidden',
                    'value' => request()->input('_locale') ?? app()->getLocale(),
                ]);
            }

            $this->Amer->setupDefaultSaveActions();
        });

        $this->Amer->operation(['list', 'show'], function () {
            $this->Amer->addButton('line', 'update', 'view', listview('buttons.update'), 'end');
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->Amer->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->Amer->getCurrentEntryId() ?? $id;
        // get the info for that entry

        $this->data['entry'] = $this->Amer->getEntryWithLocale($id);
        $this->Amer->setOperationSetting('fields', $this->Amer->getUpdateFields());

        $this->data['Amer'] = $this->Amer;
        $this->data['saveAction'] = $this->Amer->getSaveAction();
        $this->data['title'] = $this->Amer->getTitle() ?? trans('backpack::Amer.edit').' '.$this->Amer->entity_name;
        $this->data['id'] = $id;
        return view($this->Amer->getEditView(), $this->data);
    }

    /**
     * Update the specified resource in the database.
     *
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $this->Amer->hasAccessOrFail('update');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->Amer->validateRequest();
        // register any Model Events defined on fields
        $this->Amer->registerFieldEvents();
        // update the row in the db
        //dd($this->Amer->getStrippedSaveRequest($request));
        $item = $this->Amer->update(
            $request->get($this->Amer->model->getKeyName()),
            $this->Amer->getStrippedSaveRequest($request)
        );
        $this->data['entry'] = $this->Amer->entry = $item;
        \Alert::success('<i class="fa fa-check"></i> '.trans('AMER::actions.update_success'))->flash();
        $this->Amer->setSaveAction();
        return $this->Amer->performSaveAction($item->getKey());
    }
}
