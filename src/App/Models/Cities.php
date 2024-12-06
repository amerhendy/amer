<?php
namespace Amerhendy\Amer\App\Models;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Amerhendy\Amer\App\Models\Traits\AmerTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Cities extends Model
{
    use HasFactory,SoftDeletes,AmerTrait,HasRoles,HasApiTokens,HasUuids;
    protected $table = 'cities';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    public $fillable =['name','english','gov_id'];
    protected $dates = ['deleted_at'];
    public function sluggable(): array
        {
            return [
                'slug' => [
                    'source' => [],
                ],
            ];
        }
    public function employment_Job_City()
        {
            return $this->belongsToMany(Employment_Job::class, 'cities','id','id');
        }
        public function Employment_Job()

        {
            return $this->belongsToMany(Employment_Job::class, 'employment_jobs_Cities','city_id','job_id')->withTrashed();

        }
        public function Governorates()
        {
            return $this->belongsTo(Governorates::class, 'gov_id','id');
        }

}
