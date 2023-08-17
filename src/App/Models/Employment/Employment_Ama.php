<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_Ama extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_Ama';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['text'];
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];

    public function employment_Job_Ama()
    {
        return $this->belongsToMany(Employment_Job::class, 'employment_Job_Ama','id','id');
    }
    public function Employment_Job()
    {
        return $this->belongsToMany(Employment_Job::class, 'employment_Job_Ama','Ama_id','Jop_id')->withTrashed();
    }
}
