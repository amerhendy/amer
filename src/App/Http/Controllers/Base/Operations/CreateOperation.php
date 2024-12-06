<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;

use Illuminate\Support\Facades\Route;

trait CreateOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $segment  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current AmerController.
     */
    protected function setupCreateRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/create', [
            'as'        => $routeName.'.create',
            'uses'      => $controller.'@create',
            'operation' => 'create',
        ]);

        Route::post($segment, [
            'as'        => $routeName.'.store',
            'uses'      => $controller.'@store',
            'operation' => 'create',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCreateDefaults()
    {
        $this->Amer->allowAccess('create');

        $this->Amer->operation('create', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();
            $this->Amer->setupDefaultSaveActions();
        });

        $this->Amer->operation('list', function () {
            $this->Amer->addButton('top', 'create', 'view', listview('buttons.create'));
        });
    }

    /**
     * Show the form for creating inserting a new row.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->Amer->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->data['Amer'] = $this->Amer;
        $this->data['saveAction'] = $this->Amer->getSaveAction();
        $this->data['title'] = $this->Amer->getTitle() ?? trans('Amer::base.add').' '.$this->Amer->entity_name;
        return view($this->Amer->getCreateView(),['load'=>'list','data'=> $this->data],$this->data);
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->Amer->hasAccessOrFail('create');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->Amer->validateRequest();
        // register any Model Events defined on fields
        $this->Amer->registerFieldEvents();
        //dd($this->Amer->getStrippedSaveRequest($request),$request->toArray(),$this->Amer->getRequest()->toArray());
        // insert item in the db
        $item = $this->Amer->create($this->Amer->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->Amer->entry = $item;


        // show a success message
        \Alert::success('<i class="fa fa-check"></i> '.trans('AMER::actions.insert_success'))->flash();

        // save the redirect choice for next time
        $this->Amer->setSaveAction();

        return $this->Amer->performSaveAction($item->getKey());
    }
}
