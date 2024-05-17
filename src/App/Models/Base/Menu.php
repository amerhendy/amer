<?php

namespace Amerhendy\Amer\App\Models\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Menu extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'Menu';
    //protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    public $incrementing=true;
    // protected $fillable = [];
    // protected $hidden = [];
    protected $dates = ['deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
  public function parent()
    {
        return $this->belongsTo('Amerhendy\Amer\App\Models\Base\Menu', 'parent_id');
    }
    public function children()
    {
        return $this->hasMany('Amerhendy\Amer\App\Models\Base\Menu', 'parent_id');
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
