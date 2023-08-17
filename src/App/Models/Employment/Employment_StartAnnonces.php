<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_StartAnnonces extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_StartAnnonces';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
    
    //Employment_StartAnnonces
    public function employment_startannonces_Qualifications()
    {
        return $this->belongsToMany(Employment_Qualifications::class, 'Employment_StartAnnonces','id','id');
    }
    public function Employment_Qualifications()

    {
        return $this->belongsToMany(Employment_Qualifications::class, 'employment_startannonces_Qualifications','Annonce_id','Qualification_id')->withTrashed();

    }

//Employment_StartAnnonces
    public function employment_startannonces_Governorates()
    {
        return $this->belongsToMany(Governorates::class, 'Employment_StartAnnonces','id','id');
    }
    public function Governorates()

    {
        return $this->belongsToMany(Governorates::class, 'employment_startannonces_Governorates','Annonce_id','Governorate_id')->withTrashed();

    }
    public function Employment_Stages()
                {
                    return $this->hasOne(Employment_Stages::class,'id', 'Stage_id');
                }
}
