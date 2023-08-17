<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_Arm extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_Arm';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['Text'];
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
    public function employment_Job_Arm()
        {
            return $this->belongsToMany(Employment_Job::class, 'Employment_Arm','id','id');
        }
        public function Employment_Job()

        {
            return $this->belongsToMany(Employment_Job::class, 'employment_Job_Arm','Arm_id','Job_id')->withTrashed();

        }
}
