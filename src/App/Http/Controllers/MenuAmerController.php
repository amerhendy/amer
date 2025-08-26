<?php
namespace Amerhendy\Amer\App\Http\Controllers;
use \Amerhendy\Amer\App\Models\Menu as Menu;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Http\Controllers\Base\AmerController;
use \Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade as AMER;
use \Amerhendy\Amer\App\Http\Requests\MenuRequest as MenuRequest;

class MenuAmerController extends AmerController
{

    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ListOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation  {store as traitStore;}
    //use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\UpdateOperation  {update as traitUpdate;}
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\DeleteOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ShowOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ReorderOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\TrashOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CloneOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\BulkCloneOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\BulkDeleteOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\FetchOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\InlineCreateOperation;
    public function setup()
    {

        AMER::setModel(Menu::class);
        AMER::setRoute(config('Amer.Amer.route_prefix') . '/Menu');
        AMER::setEntityNameStrings(trans('AMER::Menu.singular'), trans('AMER::Menu.plural'));
        $this->Amer->setTitle(trans('AMER::Menu.create'), 'create');
        $this->Amer->setHeading(trans('AMER::Menu.create'), 'create');
        $this->Amer->setSubheading(trans('AMER::Menu.create'), 'create');
        $this->Amer->setTitle(trans('AMER::Menu.edit'), 'edit');
        $this->Amer->setHeading(trans('AMER::Menu.edit'), 'edit');
        $this->Amer->setSubheading(trans('AMER::Menu.edit'), 'edit');
        $this->Amer->addClause('where', 'deleted_at', '=', null);
        $this->Amer->enableDetailsRow ();
        $this->Amer->allowAccess ('details_row');
        $this->Amer->enableReorder('title', 3);
        if(amer_user()->can('Menu-Create') == 0){$this->Amer->denyAccess('create');}
        if(amer_user()->can('Menu-trash') == 0){$this->Amer->denyAccess ('trash');}
        //if(amer_user()->can('Menu-update') == 0){$this->Amer->denyAccess('update');}
        if(amer_user()->can('Menu-delete') == 0){$this->Amer->denyAccess('delete');}
        if(amer_user()->can('Menu-show') == 0){$this->Amer->denyAccess('show');}
    }
    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
    protected function setupListOperation(){
        AMER::addColumns([
            [
                'name'=>'title',
                'type'=>'text',
                'label'=>trans('AMER::Menu.title'),
            ],
            [
                'name'=>'icon',
                'type'=>'icon',
                'label'=>trans('AMER::Menu.icon'),
            ],
            [
                'name'=>'type',
                'type'=>'text',
                'label'=>trans('AMER::Menu.linkType'),
                'replace'=>[
                    'external_link'=>trans('AMER::Menu.external_link'),
                    'internal_link'=>trans('AMER::Menu.internal_link')
                ]
            ],
            [
                'name'=>'link',
                'type'=>'url',
                'label'=>trans('AMER::Menu.link'),
            ],
            [
                'name'=>'target',
                'type'=>'text',
                'label'=>trans('AMER::Menu.target'),
            ],
            [
                'name'=>'parent_id',
                'type'=>'select',
                'label'=>trans('AMER::Menu.parent_id'),
            ],
        ]);
    }
    function fields(){
        AMER::addField([
            'name'=>'title',
            'type'=>'text',
            'label'=>trans('AMER::Menu.singular'),
        ]);
        AMER::addField([
            'name'=>'icon',
            'type'=>'icon_picker',
            'label'=>trans('AMER::Menu.icon'),
        ]);
        AMER::addField([
            'label' => 'Parent',
            'type' => 'select',
            'name' => 'parent_id',
            'entity' => 'parent',
            'attribute' => 'title',
            'model' => Menu::class,
        ]);
        AMER::addField([
            'name' => 'type,link,page_id,target',
            'label' => 'Type',
            'type' => 'page_or_link',
            'page_model' => '\Amerhendy\PageManger\App\Models\Pages',
        ]);
    }
    protected function setupCreateOperation()
    {
        AMER::setValidation(MenuRequest::class);
        $this->fields();
    }
    protected function setupUpdateOperation()
    {
        AMER::setValidation(MenuRequest::class);
        $this->fields();
    }
    protected function setupReorderOperation()
{
    AMER::setOperationSetting('reorderColumnNames', [
        'parent_id' => 'parent_id',
        'lft' => 'lft',
        'rgt' => 'rgt',
        'depth' => 'depth',
    ]);
}
    public function destroy($id)
    {
        $this->Amer->hasAccessOrFail('delete');
        $data=$this->Amer->model::remove_force($id);
        return $data;
    }
}
