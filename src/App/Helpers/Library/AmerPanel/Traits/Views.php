<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel\Traits;

use Amerhendy\Amer\App\Helpers\LibraryExceptions\BackpackProRequiredException;
use Amerhendy\Amer\ViewNamespaces;

trait Views
{
    public function setCreateView($view){return $this->set('create.view', $view);}
    public function getCreateView(){return $this->get('create.view') ?? mainview('main.Layouts.create');}
	/////content////////////
    public function setCreateContentClass(string $class){return $this->set('create.contentClass', $class);}
    public function getCreateContentClass(){return $this->get('create.contentClass') ?? config('Amer.base.create.contentClass', 'col-md-8 bold-labels');}
	/////list////////////
	public function setListView($view){return $this->set('list.view', $view);}
    /////////////////////////show here//////////////////////////////////
	public function getListView(){return $this->get('list.view') ?? mainview('main.Layouts.list');}
	public function setListContentClass(string $class){return $this->set('list.contentClass', $class);}
	public function getListContentClass(){return $this->get('list.contentClass') ?? config('Amer.base.list.contentClass', 'col-md-12');}
	/////destails////////////
    public function setDetailsRowView($view){return $this->set('list.detailsRow.view', $view);}
    public function getDetailsRowView(){return $this->get('list.detailsRow.view') ?? mainview('main.Layouts.details_row');}
    public function setShowView($view){return $this->set('show.view', $view);}
    /////////////show here////////////////////
    public function getShowView(){return $this->get('show.view') ?? mainview('main.Layouts.show');}

    public function setShowContentClass(string $class){return $this->set('show.contentClass', $class);}
    public function getShowContentClass()
    {return $this->get('show.contentClass') ?? config('Amer.base.show.contentClass', 'col-md-8 col-md-offset-2');}
	////////////////////////////////
    // -------
    // UPDATE
    // -------
    public function setEditView($view){return $this->set('update.view', $view);}
    public function getEditView(){return $this->get('update.view') ?? mainview('main.Layouts.create');}
	//////////////////////
    public function setEditContentClass(string $class){return $this->set('update.contentClass', $class);}
    public function getEditContentClass(){return $this->get('update.contentClass') ?? config('Amer.Base.update.contentClass', 'col-md-8 bold-labels');}
	//////////////////////////////////
    public function setReorderView($view){return $this->set('reorder.view', $view);}
    public function getReorderView(){return $this->get('reorder.view') ?? mainview('main.Layouts.reorder');}
	/////////////////////////////////////
    public function setReorderContentClass(string $class){return $this->set('reorder.contentClass', $class);}
    public function getReorderContentClass(){return $this->get('reorder.contentClass') ?? config('Amer.Base.reorder.contentClass', 'col-md-8 col-md-offset-2');}
	////////////////////////////////////////////////////////
    // -------
    // ALIASES
    // -------

    public function getPreviewView(){return $this->getShowView();}
    public function setPreviewView($view){return $this->setShowView($view);}
	//////////////////////////
    public function getUpdateView(){return $this->getEditView();}
    public function setUpdateView($view){return $this->setEditView($view);}
	//////////////////////////
	public function setUpdateContentClass(string $editContentClass){return $this->setEditContentClass($editContentClass);}
	public function getUpdateContentClass(){return $this->getEditContentClass();}
	//////////////////////////////////////////////////////////////////////////////
    // -------
    // FIELDS
    // -------
    public function getFirstFieldView($viewPath, $viewNamespace = false)
    {
        if ($viewNamespace) {
            return $viewNamespace.'.'.$viewPath;
        }
        $paths = array_map(function ($item) use ($viewPath) {
            return $item.'.'.$viewPath;
        }, ViewNamespaces::getFor('fields'));
        foreach ($paths as $path) {

            if (view()->exists($path)) {
                return $path;
            }
        }
    }
}
