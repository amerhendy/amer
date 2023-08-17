<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_Qualifications extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_Qualifications';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
        //Employment_Qualifications
        public function employment_Job_Qualifications()
        {
            return $this->belongsToMany(Employment_Job::class, 'Employment_Qualifications','id','id');
        }
        public function Employment_Job()

        {
            return $this->belongsToMany(Employment_Job::class, 'employment_Job_Qualifications','Qualifications_id','Job_id')->withTrashed();

        }

    //Employment_Qualifications
        public function employment_startannonces_Qualifications()
        {
            return $this->belongsToMany(Employment_StartAnnonces::class, 'Employment_Qualifications','id','id');
        }
        public function Employment_StartAnnonces()

        {
            return $this->belongsToMany(Employment_StartAnnonces::class, 'employment_startannonces_Qualifications','Qualification_id','Annonce_id')->withTrashed();

        }
}
