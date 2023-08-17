<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_PeopleNewStage extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_PeopleNewStage';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
    public function Employment_Stages()
                {
                    return $this->hasOne(Employment_Stages::class,'id', 'Stage_id');
                }
            
}
