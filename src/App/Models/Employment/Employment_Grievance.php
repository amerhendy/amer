<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_Grievance extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_Grievance';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
    //Employment_Grievance

    public function Employment_People()
    {
        return $this->hasOne(Employment_People::class,'id', 'Uid');
    }


public function Employment_Stages()
    {
        return $this->hasOne(Employment_Stages::class,'id', 'Stage_id');
    }

}
