<?php

namespace Amerhendy\Amer\App\Models\Traits;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| Methods for storing uploaded files (used in amer).
|--------------------------------------------------------------------------
*/
trait BindsDynamically
{
    protected $connection = null;
    protected $table = null;

    public function bind(string $connection, string $table)
    {
       $this->setConnection($connection);
       $this->setTable($table);
    }

    public function newInstance($attributes = [], $exists = false)
    {
       // Overridden in order to allow for late table binding.

       $model = parent::newInstance($attributes, $exists);
       $model->setTable($this->table);

       return $model;
    }
}
