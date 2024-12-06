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
        $path=config('Amer.Amer.package_path').'App\models';
        new AmerHelper($path);
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
    public static function dimd($text){
        $dims=['.','_','-',"'",'"',':',';',',','0','1','2','3','4','5','6','7','8','9',' '];
        $re=[];
        if(\Str::of($text)->contains($dims)){
            for($i=0;$i<=count($dims)-1;$i++){
                if(\Str::contains($text, $dims[$i])){
                    $re[]=$dims[$i];
                }
            }
            for($i=0;$i<=count($re)-1;$i++){
                $text=\Str::replace($re[$i], '_', $text);
            }
            $text=explode('_',$text);
            return $text;
        }
        return [$text];
    }
    public static function namingconventions($text){
        $text=self::dimd($text);
        $ret=[];
        $controller=$text;
        $tbler=[];
        $ptble=[];
        $Variables=[];
        $model=[];
        $route=[];
        for($i=0;$i<=count($text)-1;$i++){
            if($i==0){
                $route[$i]=\Str::lower(\Str::pluralStudly($text[$i]));
                $tbler[$i]=\Str::lower(\Str::pluralStudly($text[$i]));
                if(count($text) == 1){
                    $Variables[$i]=\Str::lower(\Str::pluralStudly($text[$i]));
                }else{
                    $Variables[$i]=\Str::lower(\Str::singular($text[$i]));
                }
            }else{
                $tbler[$i]=\Str::lower(\Str::pluralStudly($text[$i]));
                $Variables[$i]=\Str::camel(\Str::singular($text[$i]));
                $route[$i]=\Str::lower(\Str::pluralStudly($text[$i]));
            }
            $ptble[$i]=\Str::lower(\Str::singular($text[$i]));
            $model[$i]=\str::studly(\Str::singular($text[$i]));
        }

        $controller=implode('',$controller);
        $model=implode('',$model);
        $tble=implode('_',$tbler);
        $ptble=implode('_',$ptble);
        $route=implode('',$route);
        $Variables=\Str::camel("$".implode('_',$Variables));
        $ret['table']=$tble;
        $ret['Pivot tables']=$ptble;
        $ret['Table columns names']=['id','created_at','post_body','post_id'];
        $res['Variables']=$Variables;
        $ret['Controller']=$controller;
        $ret['Model']=$model;
        $ret['Model properties']='$this->blog_title';
        $ret['Model Method']="public function getAll()";
        $ret['Relationships']=['postAuthor()','phone()'];
        $ret['route']=$route;
        return $ret;
    }
}
