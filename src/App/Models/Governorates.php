<?php
namespace Amerhendy\Amer\App\Models;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Amerhendy\Amer\App\Models\Traits\AmerTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Governorates extends Model
{
    use HasFactory,SoftDeletes,AmerTrait,HasRoles,HasApiTokens,Sluggable, SluggableScopeHelpers,HasUuids;
    protected $table = 'governorates';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $fillable=['name','english'];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    public function sluggable(): array
        {
            return [
                'slug' => [
                    'source' => [],
                ],
            ];
        }
        public function Employment_StartAnnonces_Governorates()
        {
            return $this->belongsToMany(\Amerhendy\Employment\App\Models\Employment_StartAnnonces::class, 'governorates','id','id');
        }
        public function Employment_StartAnnonces()

        {
            return $this->belongsToMany(\Amerhendy\Employment\App\Models\Employment_StartAnnonces::class, "employment_startannonces_governorates","governorate_id","annonce_id")->withTrashed();

        }
        public function Cities()
        {
            return $this->hasMany(Cities::class,'gov_id','id');
        }
}
