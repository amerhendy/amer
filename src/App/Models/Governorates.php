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
    protected $table = 'Governorates';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $fillable=['Name','English'];
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
            return $this->belongsToMany(\Amerhendy\Employment\App\Models\Employment_StartAnnonces::class, 'Governorates','id','id');
        }
        public function Employment_StartAnnonces()
    
        {
            return $this->belongsToMany(\Amerhendy\Employment\App\Models\Employment_StartAnnonces::class, "Employment_StartAnnonces_Governorates","Governorate_id","Annonce_id")->withTrashed();
    
        }
        public function Cities()
        {
            return $this->hasMany(Cities::class,'Gov_id','id');
        }
}
