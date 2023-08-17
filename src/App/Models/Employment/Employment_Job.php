<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_Job extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_Job';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['Annonce_id','Code','Name','JobName','JobDescription','Slug','Functional_id','Count','AgeIn','Age','Driver','Statue'];
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
        //Employment_Job
        public function employment_Job_Ama()
        {
            return $this->belongsToMany(Employment_Ama::class, 'Employment_Job','id','id');
        }
        public function Employment_Ama()

        {
            return $this->belongsToMany(Employment_Ama::class, 'employment_Job_Ama','Job_id','Ama_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_Arm()
        {
            return $this->belongsToMany(Employment_Arm::class, 'Employment_Job','id','id');
        }
        public function Employment_Arm()

        {
            return $this->belongsToMany(Employment_Arm::class, 'employment_Job_Arm','Job_id','Arm_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_City()
        {
            return $this->belongsToMany(Cities::class, 'Employment_Job','id','id');
        }
        public function Cities()

        {
            return $this->belongsToMany(Cities::class, 'employment_Job_City','Job_id','City_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_Driver()
        {
            return $this->belongsToMany(Employment_Driver::class, 'Employment_Job','id','id');
        }
        public function Employment_Driver()

        {
            return $this->belongsToMany(Employment_Driver::class, 'employment_Job_Driver','Job_id','Driver_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_Education()
        {
            return $this->belongsToMany(Employment_Education::class, 'Employment_Job','id','id');
        }
        public function Employment_Education()

        {
            return $this->belongsToMany(Employment_Education::class, 'employment_Job_Education','Job_id','Education_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_Health()
        {
            return $this->belongsToMany(Employment_Health::class, 'Employment_Job','id','id');
        }
        public function Employment_Health()

        {
            return $this->belongsToMany(Employment_Health::class, 'employment_Job_Health','Job_id','Health_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_IncludedFiles()
        {
            return $this->belongsToMany(Employment_IncludedFiles::class, 'Employment_Job','id','id');
        }
        public function Employment_IncludedFiles()

        {
            return $this->belongsToMany(Employment_IncludedFiles::class, 'employment_Job_IncludedFiles','Job_id','IncludedFiles_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_Instructions()
        {
            return $this->belongsToMany(Employment_Instructions::class, 'Employment_Job','id','id');
        }
        public function Employment_Instructions()

        {
            return $this->belongsToMany(Employment_Instructions::class, 'employment_Job_Instructions','Job_id','Instructions_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_Mir()
        {
            return $this->belongsToMany(Employment_Mir::class, 'Employment_Job','id','id');
        }
        public function Employment_Mir()

        {
            return $this->belongsToMany(Employment_Mir::class, 'employment_Job_Mir','Job_id','Mir_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_Places()
        {
            return $this->belongsToMany(Employment_Places::class, 'Employment_Job','id','id');
        }
        public function Employment_Places()

        {
            return $this->belongsToMany(Employment_Places::class, 'employment_Job_Places','Job_id','Places_id')->withTrashed();

        }

    //Employment_Job
        public function employment_Job_Qualifications()
        {
            return $this->belongsToMany(Employment_Qualifications::class, 'Employment_Job','id','id');
        }
        public function Employment_Qualifications()

        {
            return $this->belongsToMany(Employment_Qualifications::class, 'employment_Job_Qualifications','Job_id','Qualifications_id')->withTrashed();

        }
        public function Employment_StartAnnonces()
        {
            return $this->hasOne(Employment_StartAnnonces::class,'id', 'Annonce_id');
        }
    

    public function Employment_FunctionalClass()
        {
            return $this->hasOne(Employment_FunctionalClass::class,'id', 'Functional_id');
        }
    

}
