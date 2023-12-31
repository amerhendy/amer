<?php
//namespace amerhendy\Amer;
namespace amerhendy\Amer\App\Http\Controllers\api;
use AmerHelper;
//namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
class allcollection extends Controller
{
    public function __construct(){
        new AmerHelper(config('employers.package_path').'\app\models');
    }
    public static function index($class,Request $request){
        AmerHelper::$currentClass=$class;
        $modelinfo=AmerHelper::loadmodels($class);
        if(empty($modelinfo)){return AmerHelper::responsedata($modelinfo);}
        $classlink=new $modelinfo['callLink'] ();
        $classlink=$classlink->get();
        $data=AmerHelper::removeCreatedUpdatedDeleted($classlink);
        return AmerHelper::responsedata($data);
    }
}
