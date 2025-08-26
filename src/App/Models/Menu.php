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
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Menu extends Model
{
    use HasFactory,SoftDeletes,AmerTrait,HasRoles,HasApiTokens,HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'menu';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $fillable=['title','icon','protocol','link','target','parent_id','lft','rgt','depth'];
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
  public function parent()
    {
        return $this->belongsTo('Amerhendy\Amer\App\Models\Menu', 'parent_id');
    }
    public function children()
    {
        return $this->hasMany('Amerhendy\Amer\App\Models\Menu', 'parent_id');
    }
 public static function getTree()
    {
        $Menu = self::orderBy('lft')->get();

        if ($Menu->count()) {
            foreach ($Menu as $k => $Menu_item) {
                $Menu_item->children = collect([]);

                foreach ($Menu as $i => $Menu_subitem) {
                    if ($Menu_subitem->parent_id == $Menu_item->id) {
                        $Menu_item->children->push($Menu_subitem);

                        // remove the subitem for the first level
                        $Menu = $Menu->reject(function ($item) use ($Menu_subitem) {
                            return $item->id == $Menu_subitem->id;
                        });
                    }
                }
            }
        }

        return $Menu;
    }
     public function url()
    {
        switch ($this->type) {
            case 'external_link':
                return $this->link;
                break;
            case 'internal_link':
                return is_null($this->link) ? '#' : url($this->link);
                break;
            case 'email':
                return 'mailto:'.$this->link;
                break;
            case 'nolink':
                return '#';
                break;
            default: //page_link
                if ($this->page) {
                    return url($this->page->slug);
                }
                break;
        }
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
