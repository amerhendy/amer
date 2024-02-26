<?php
namespace Amerhendy\Amer\App\Http\Controllers;
use \Amerhendy\Amer\App\Models\Governorates as Governorates;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Http\Controllers\Base\AmerController;
use \Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade as AMER;
use \Amerhendy\Amer\App\Http\Requests\GovernoratesRequest as GovernoratesRequest;

class GovernoratesAmerController extends AmerController
{
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ListOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation  {store as traitStore;}
    //use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\UpdateOperation  {update as traitUpdate;}
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
        AMER::setModel(Governorates::class);
        AMER::setRoute(config('Amer.amer.route_prefix') . '/Governorates');
        AMER::setEntityNameStrings(trans('AMER::Governorates.singular'), trans('AMER::Governorates.plural'));
        /*
        $this->Amer->setTitle(trans('AMER::Governorates.create'), 'create');
        $this->Amer->setHeading(trans('AMER::Governorates.create'), 'create');
        $this->Amer->setSubheading(trans('AMER::Governorates.create'), 'create');
        $this->Amer->setTitle(trans('AMER::Governorates.edit'), 'edit');
        $this->Amer->setHeading(trans('AMER::Governorates.edit'), 'edit');
        $this->Amer->setSubheading(trans('AMER::Governorates.edit'), 'edit');
        $this->Amer->addClause('where', 'deleted_at', '=', null);
        $this->Amer->enableDetailsRow ();
        $this->Amer->allowAccess ('details_row');
        if(amer_user()->can('Governorates-add') == 0){$this->Amer->denyAccess('create');}
        if(amer_user()->can('Governorates-trash') == 0){$this->Amer->denyAccess ('trash');}
        if(amer_user()->can('Governorates-update') == 0){$this->Amer->denyAccess('update');}
        if(amer_user()->can('Governorates-delete') == 0){$this->Amer->denyAccess('delete');}
        if(amer_user()->can('Governorates-show') == 0){$this->Amer->denyAccess('show');}
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
                'label'=>trans('AMER::Governorates.singular'),
            ],
            [
                'name'=>'English',
                'type'=>'text',
                'label'=>trans('AMER::Governorates.singular'),
            ],
            [
                'name'=>'Cities',
                'type'=>'select',
                'label'=>trans('AMER::Cities.plural'),
                'model'=>\Amerhendy\Amer\App\Models\Cities::class,
                'entity'=>'Cities',
                'attribute'=>'Name'
            ]
        ]);
    }
    function fields(){
        AMER::addField([
            'name'=>'Name',
            'type'=>'text',
            'label'=>trans('AMER::Governorates.singular'),
        ]);
        AMER::addField([
            'name'=>'English',
            'type'=>'text',
            'label'=>trans('AMER::Governorates.singular'),
        ]);
        
    }
    protected function setupCreateOperation()
    {
        AMER::setValidation(GovernoratesRequest::class);
        $this->fields();
    }
    protected function setupUpdateOperation()
    {
        AMER::setValidation(GovernoratesRequest::class);
        $this->fields();
    }
    public function store(GovernoratesRequest $request)
    {
        //$table=$this->Amer->model->getTable();
        //$lsid=DB::table($table)->get()->max('id');
        //$id=$lsid+1;
        //$this->Amer->addField(['type' => 'hidden', 'name' => 'id', 'value'=>$id]);
        //$this->Amer->getRequest()->request->add(['id'=> $id]);
        //$this->Amer->setRequest($this->Amer->validateRequest());
 //       $this->Amer->unsetValidation();
        return $this->traitStore();
    }
    public function destroy($id)
    {
        $this->Amer->hasAccessOrFail('delete');
        $data=$this->Amer->model::remove_force($id);
        return $data;
    }
    public function fetchCities()
    {
        return $this->fetch(['model'=>\Amerhendy\Amer\App\Models\Cities::class,'searchable_attributes'=>'Name']);
    }
}