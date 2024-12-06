<?php

namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;

use Illuminate\Support\Facades\Route;

trait ReorderOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $name  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current AmerController.
     */
    protected function setupReourderRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/reorder', [
            'as' => $routeName.'.reorder',
            'uses' => $controller.'@reorder',
            'operation' => 'reorder',
        ]);

        Route::post($segment.'/reorder', [
            'as' => $routeName.'.save.reorder',
            'uses' => $controller.'@saveReorder',
            'operation' => 'reorder',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupReorderDefaults()
    {
        $this->Amer->set('reorder.enabled', true);
        $this->Amer->allowAccess('reorder');
        $this->Amer-> operation('reorder', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();
            $this->Amer->setOperationSetting('reorderColumnNames', [
                'parent_id' => 'parent_id',
                'lft' => 'lft',
                'rgt' => 'rgt',
                'depth' => 'depth',
            ]);
        });
        $this->Amer->operation(['list', 'show'], function () {
            $this->Amer->addButton('top', 'reorder', 'view', listview('buttons.reorder'));
        });
    }
    public function reorder()
    {
        $this->Amer->hasAccessOrFail('reorder');
        if (! $this->Amer->isReorderEnabled()) {
            abort(403, 'Reorder is disabled.');
        }
        // get all results for that entity
        $this->data['entries'] = $this->Amer->getEntries();
        $this->data['Amer'] = $this->Amer;
        $this->data['title'] = $this->Amer->getTitle() ?? trans('AMER::actions.reorder').' '.$this->Amer->entity_name;
        return view($this->Amer->getReorderView(), $this->data);
    }

    /**
     * Save the new order, using the Nested Set pattern.
     *
     * Database columns needed: id, parent_id, lft, rgt, depth, name/title
     *
     * @return
     */
    public function saveReorder()
    {
        $this->Amer->hasAccessOrFail('reorder');
        $all_entries = json_decode(\Request::input('tree'), true);
        if (count($all_entries)) {
            $count = $this->Amer->updateTreeOrder($all_entries);
        } else {
            return false;
        }
        return \AmerHelper::responsedata($all_entries,$draw=null,$classcount=null,$recordsFiltered=null);
        return 'success for '.$count.' items';
    }
}
