<?php

namespace Amerhendy\Employment\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Cities extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Cities';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
    public function employment_Job_City()
        {
            return $this->belongsToMany(Employment_Job::class, 'Cities','id','id');
        }
        public function Employment_Job()

        {
            return $this->belongsToMany(Employment_Job::class, 'employment_Job_City','City_id','Job_id')->withTrashed();

        }
        public function Governorates()
                {
                    return $this->hasOne(Governorates::class,'id', 'Gov_id');
                }
            
}
