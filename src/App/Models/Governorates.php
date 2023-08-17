<?php

namespace Amerhendy\Employment\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Governorates extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Governorates';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
    public function employment_startannonces_Governorates()
    {
        return $this->belongsToMany(Employment_StartAnnonces::class, 'Governorates','id','id');
    }
    public function Employment_StartAnnonces()

    {
        return $this->belongsToMany(Employment_StartAnnonces::class, 'employment_startannonces_Governorates','Governorate_id','Annonce_id')->withTrashed();

    }
}
