<?php
namespace Amerhendy\Amer\App\Http\Controllers;
use \Amerhendy\Amer\App\Models\Cities as Cities;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Http\Controllers\Base\AmerController;
use \Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade as AMER;
use \Amerhendy\Amer\App\Http\Requests\CitiesRequest as CitiesRequest;

class CitiesAmerController extends AmerController
{
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ListOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation;
    //use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\UpdateOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\DeleteOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ShowOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\TrashOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CloneOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\BulkCloneOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\BulkDeleteOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\FetchOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\InlineCreateOperation;
    public function setup()
    {
        AMER::setModel(Cities::class);
        AMER::setRoute(config('Amer.amer.route_prefix') . '/Cities');
        AMER::setEntityNameStrings(trans('AMER::Cities.singular'), trans('AMER::Cities.plural'));
        /*
        $this->Amer->setTitle(trans('AMER::Cities.create'), 'create');
        $this->Amer->setHeading(trans('AMER::Cities.create'), 'create');
        $this->Amer->setSubheading(trans('AMER::Cities.create'), 'create');
        $this->Amer->setTitle(trans('AMER::Cities.edit'), 'edit');
        $this->Amer->setHeading(trans('AMER::Cities.edit'), 'edit');
        $this->Amer->setSubheading(trans('AMER::Cities.edit'), 'edit');
        $this->Amer->addClause('where', 'deleted_at', '=', null);
        $this->Amer->enableDetailsRow ();
        $this->Amer->allowAccess ('details_row');
        if(amer_user()->can('Cities-add') == 0){$this->Amer->denyAccess('create');}
        if(amer_user()->can('Cities-trash') == 0){$this->Amer->denyAccess ('trash');}
        if(amer_user()->can('Cities-update') == 0){$this->Amer->denyAccess('update');}
        if(amer_user()->can('Cities-delete') == 0){$this->Amer->denyAccess('delete');}
        if(amer_user()->can('Cities-show') == 0){$this->Amer->denyAccess('show');}
        */
    }
    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
    protected function setupListOperation(){
        AMER::addColumns([
            [
                'name'=>'Name',
                'type'=>'text',
                'label'=>trans('AMER::Cities.singular'),
            ],
            [
                'name'=>'English',
                'type'=>'text',
                'label'=>trans('AMER::Cities.singular'),
            ],
            [
                'name'=>'Governorates',
                'type'=>'select',
                'label'=>trans('AMER::Governorates.plural'),
                'model'=>\Amerhendy\Amer\App\Models\Governorates::class,
                'entity'=>'Governorates',
                'attribute'=>'Name'
            ]
        ]);
    }
    function fields(){
        AMER::addField([
            'name'=>'Name',
            'type'=>'text',
            'label'=>trans('AMER::Cities.singular'),
        ]);
        AMER::addField([
            'name'=>'English',
            'type'=>'text',
            'label'=>trans('AMER::Cities.singular'),
        ]);
        $routes=$this->Amer->routelist;
        AMER::addField([
            'name'=>'Governorates',
            'type' => "relationship",
            'model'=>\Amerhendy\Amer\App\Models\Governorates::class,
            'attribute'=>'Name',
            'include_all_form_fields'=>true,
            'inline_create'=>true,
            //'ajax'=>$routes['fetchCities']['as'],
            'data_source'=>$routes['fetchGovernorates']['as'],
        ]);
    }
    protected function setupCreateOperation()
    {
        AMER::setValidation(CitiesRequest::class);
        $this->fields();
    }
    protected function setupUpdateOperation()
    {
        AMER::setValidation(CitiesRequest::class);
        $this->fields();
    }
    public function store(CitiesRequest $request)
    {
        $table=$this->Amer->model->getTable();
        $lsid=DB::table($table)->get()->max('id');
        $id=$lsid+1;
        $this->Amer->addField(['type' => 'hidden', 'name' => 'id', 'value'=>$id]);
        $this->Amer->getRequest()->request->add(['id'=> $id]);
        $this->Amer->setRequest($this->Amer->validateRequest());
        $this->Amer->unsetValidation();
        return $this->traitStore();
    }
    public function destroy($id)
    {
        $this->Amer->hasAccessOrFail('delete');
        $data=$this->Amer->model::remove_force($id);
        return $data;
    }
    public function fetchGovernorates()
    {
        return $this->fetch(['model'=>\Amerhendy\Amer\App\Models\Governorates::class,'searchable_attributes'=>'Name']);
    }
}