<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_PeopleDegree extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_PeopleDegree';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
    public function Employment_People()
    {
        return $this->hasOne(Employment_People::class,'id', 'Uid');
    }
}
