<?php
namespace Amerhendy\Amer\App\Http\Controllers\Base\Operations;
use Illuminate\Support\Facades\Route;
trait ListOperation
{
    protected function setupListRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/', [
            'as'        => $routeName.'.index',
            'uses'      => $controller.'@index',
            'operation' => 'list',
        ]);

        Route::post($segment.'/search', [
            'as'        => $routeName.'.search',
            'uses'      => $controller.'@search',
            'operation' => 'list',
        ]);
        Route::post($segment.'/{id}/details', [
            'as'        => $routeName.'.showDetailsRow',
            'uses'      => $controller.'@showDetailsRow',
            'operation' => 'list',
        ]);
    }
    protected function setupListDefaults()
    {
        $this->Amer->allowAccess('list');

        $this->Amer->operation('list', function () {
            $this->Amer->loadDefaultOperationSettingsFromConfig();
        });

    }

    /**
     * Display all rows in the database for this entity.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->Amer->hasAccessOrFail('list');

        $this->data['Amer'] = $this->Amer;
        $this->data['title'] = $this->Amer->getTitle() ?? mb_ucfirst($this->Amer->entity_name_plural);
        return view($this->Amer->getListView(),['load'=>'list','data'=> $this->data],$this->data);
    }
    public function search()
    {
        //dd(request());
        $this->Amer->hasAccessOrFail('list');
        $this->Amer->applyUnappliedFilters();
        $start = (int) request()->input('start');
        $length = (int) request()->input('length');
        $search = request()->input('search');
        if($length === 0){$length=$this->Amer->getDefaultPageLength();}
        // if a search term was present
        if ($search && $search['value'] ?? false) {
            // filter the results accordingly
            $this->Amer->applySearchTerm($search['value']);
        }
        // start the results according to the datatables pagination
        if ($start) {
            $this->Amer->skip($start);
        }
        // limit the number of results according to the datatables pagination
        if ($length) {
            $this->Amer->take($length);
        }
        // overwrite any order set in the setup() method with the datatables order
        $this->Amer->applyDatatableOrder();

        $entries = $this->Amer->getEntries();
        // if show entry count is disabled we use the "simplePagination" technique to move between pages.
        if ($this->Amer->getOperationSetting('showEntryCount')) {
            $totalEntryCount = (int) (request()->get('totalEntryCount') ?: $this->Amer->getTotalQueryCount());
            $filteredEntryCount = $this->Amer->getFilteredQueryCount() ?? $totalEntryCount;
        } else {
            $totalEntryCount = $length;
            $filteredEntryCount = $entries->count() < $length ? 0 : $length + $start + 1;
        }

        // store the totalEntryCount in AmerPanel so that multiple blade files can access it
        $this->Amer->setOperationSetting('totalEntryCount', $totalEntryCount);
        return $this->Amer->getEntriesAsJsonForDatatables($entries, $totalEntryCount, $filteredEntryCount, $start);
    }

    /**
     * Used with AJAX in the list view (datatables) to show extra information about that row that didn't fit in the table.
     * It defaults to showing some dummy text.
     *
     * @return \Illuminate\View\View
     */
    public function showDetailsRow($id)
    {
        $this->Amer->hasAccessOrFail('list');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->Amer->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->Amer->getEntry($id);
        $this->data['Amer'] = $this->Amer;

        // load the view
        return view($this->Amer->getDetailsRowView(), $this->data);
    }
}
