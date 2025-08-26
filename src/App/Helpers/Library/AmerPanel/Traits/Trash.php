<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel\Traits;

trait Trash
{
    public function Trash($id)
    {
        return (string) $this->model->findOrFail($id)->delete();
    }
}
