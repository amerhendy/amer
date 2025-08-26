<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;

use Illuminate\Support\Facades\Route;

trait ShowOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $segment  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current AmerController.
     */
    protected function setupShowRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/show', [
            'as'        => $routeName.'.show',
            'uses'      => $controller.'@show',
            'operation' => 'show',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupShowDefaults()
    {
        $this->Amer->allowAccess('show');
        $this->Amer->setOperationSetting('setFromDb', true);

        $this->Amer->operation('show', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();

            if (! method_exists($this, 'setupShowOperation')) {
                $this->autoSetupShowOperation();
            }
        });

        $this->Amer->operation('list', function () {
            $req=$this->Amer->getRequest();
            if($req->bearerToken() == ""){
                $this->Amer->addButton('line', 'show', 'view', listview('buttons.show'), 'beginning');
            }else{
                $this->Amer->addButton('line', 'show', 'view', 'buttons.show');
            }
        });

        $this->Amer->operation(['create', 'update'], function () {
            $this->Amer->addSaveAction([
                'name' => 'save_and_preview',
                'visible' => function ($Amer) {
                    return $Amer->hasAccess('show');
                },
                'redirect' => function ($Amer, $request, $itemId = null) {
                    $itemId = $itemId ?: $request->input('id');
                    $redirectUrl = $Amer->route.'/'.$itemId.'/show';
                    if ($request->has('_locale')) {
                        $redirectUrl .= '?_locale='.$request->input('_locale');
                    }

                    return $redirectUrl;
                },
                'button_text' => trans('AMER::actions.save_action_save_and_preview'),
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $this->Amer->hasAccessOrFail('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->Amer->getCurrentEntryId() ?? $id;

        // get the info for that entry (include softDeleted items if the trait is used)
        if ($this->Amer->get('show.softDeletes') && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->Amer->model))) {
            $this->data['entry'] = $this->Amer->getModel()->withTrashed()->findOrFail($id);
        } else {
            $this->data['entry'] = $this->Amer->getEntryWithLocale($id);
        }

        $this->data['Amer'] = $this->Amer;
        $this->data['title'] = $this->Amer->getTitle() ?? trans('AMER::base.preview').' '.$this->Amer->entity_name;
        return view($this->Amer->getShowView(), $this->data);
    }

    /**
     * Default behaviour for the Show Operation, in case none has been
     * provided by including a setupShowOperation() method in the AmerController.
     */
    protected function autoSetupShowOperation()
    {
        // guess which columns to show, from the database table
        if ($this->Amer->get('show.setFromDb')) {
            $this->Amer->setFromDb(false, true);
        }

        // if the model has timestamps, add columns for created_at and updated_at
        if ($this->Amer->get('show.timestamps') && $this->Amer->model->usesTimestamps()) {
            $this->Amer->column($this->Amer->model->getCreatedAtColumn())->type('datetime');
            $this->Amer->column($this->Amer->model->getUpdatedAtColumn())->type('datetime');
        }

        // if the model has SoftDeletes, add column for deleted_at
        if ($this->Amer->get('show.softDeletes') && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->Amer->model))) {
            $this->Amer->column($this->Amer->model->getDeletedAtColumn())->type('datetime');
        }

        // remove the columns that usually don't make sense inside the Show operation
        $this->removeColumnsThatDontBelongInsideShowOperation();
    }

    protected function removeColumnsThatDontBelongInsideShowOperation()
    {
        // cycle through columns
        foreach ($this->Amer->columns() as $key => $column) {
            // remove any autoset relationship columns
            if (array_key_exists('model', $column) && array_key_exists('autoset', $column) && $column['autoset']) {
                $this->Amer->removeColumn($column['key']);
            }

            // remove any autoset table columns
            if ($column['type'] == 'table' && array_key_exists('autoset', $column) && $column['autoset']) {
                $this->Amer->removeColumn($column['key']);
            }

            // remove the row_number column, since it doesn't make sense in this context
            if ($column['type'] == 'row_number') {
                $this->Amer->removeColumn($column['key']);
            }

            // remove columns that have visibleInShow set as false
            if (isset($column['visibleInShow'])) {
                if ((is_callable($column['visibleInShow']) && $column['visibleInShow']($this->data['entry']) === false) || $column['visibleInShow'] === false) {
                    $this->Amer->removeColumn($column['key']);
                }
            }

            // remove the character limit on columns that take it into account
            if (in_array($column['type'], ['text', 'email', 'model_function', 'model_function_attribute', 'phone', 'row_number', 'select'])) {
                $this->Amer->modifyColumn($column['key'], ['limit' => ($column['limit'] ?? 999)]);
            }
        }

        // remove bulk actions colums
        $this->Amer->removeColumns(['blank_first_column', 'bulk_actions']);
    }
}
