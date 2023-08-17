<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_PeopleNewData extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_PeopleNewData';
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


public function Employment_Stages()
    {
        return $this->hasOne(Employment_Stages::class,'id', 'Stage_id');
    }


public function Employment_Job()
    {
        return $this->hasOne(Employment_Job::class,'id', 'Job_id');
    }


public function Governorates()
    {
        return $this->hasOne(Governorates::class,'id', 'Born_gov');
    }


public function Cities()
    {
        return $this->hasOne(Cities::class,'id', 'Born_city');
    }


public function Governorates()
    {
        return $this->hasOne(Governorates::class,'id', 'Live_gov');
    }


public function Cities()
    {
        return $this->hasOne(Cities::class,'id', 'Live_city');
    }


public function Employment_Health()
    {
        return $this->hasOne(Employment_Health::class,'id', 'Health_id');
    }


public function Employment_Mir()
    {
        return $this->hasOne(Employment_Mir::class,'id', 'Mir_id');
    }


public function Employment_Arm()
    {
        return $this->hasOne(Employment_Arm::class,'id', 'Arm_id');
    }


public function Employment_Ama()
    {
        return $this->hasOne(Employment_Ama::class,'id', 'Ama_id');
    }


public function Employment_Education()
    {
        return $this->hasOne(Employment_Education::class,'id', 'Education_id');
    }


public function Employment_Driver()
    {
        return $this->hasOne(Employment_Driver::class,'id', 'DriverDegree_id');
    }

}
