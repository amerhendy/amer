<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel\Traits;

trait Delete
{
    /*
    |--------------------------------------------------------------------------
    |                                   DELETE
    |--------------------------------------------------------------------------
    */

    /**
     * Delete a row from the database.
     *
     * @param  int  $id  The id of the item to be deleted.
     * @return bool True if the item was deleted.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException if the model was not found.
     *
     * TODO: should this delete items with relations to it too?
     */
    public function delete($id)
    {
        return (string) $this->model->findOrFail($id)->forcedelete();
    }

    /**
     * Add the bulk delete button to the bottom stack.
     */
    public function addBulkDeleteButton()
    {
        // bottom stack
        $this->addButton('bottom', 'bulk_delete', 'view', 'Amer::buttons.bulk_delete');
    }
}
