<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_IncludedFiles extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_IncludedFiles';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['Checked','Text'];
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
    public function employment_Job_IncludedFiles()
        {
            return $this->belongsToMany(Employment_Job::class, 'Employment_IncludedFiles','id','id');
        }
        public function Employment_Job()

        {
            return $this->belongsToMany(Employment_Job::class, 'employment_Job_IncludedFiles','IncludedFiles_id','Job_id')->withTrashed();

        }
}
