<?php

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employment_PagesStatic extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'Employment_PagesStatic';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['Title','Content','Data'];
    protected $dates = ['deleted_at'];
    public static $list=[];
    public static $fileds=[];
}
