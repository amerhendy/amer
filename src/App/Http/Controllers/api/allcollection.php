<?php
//namespace amerhendy\Amer;
namespace amerhendy\Amer\App\Http\Controllers\api;
use AmerHelper;
//namespace App\Http\Controllers;
use Amerhendy\Amer\App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
class allcollection extends Controller
{
    public function __construct(){
        $path=config('Amer.Amer.package_path').'App\Models';
        new AmerHelper(cleanDir($path));
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


public function getMenuTree()
{
    $start = microtime(true);

    // ✅ هنا لو موجود في الكاش هيرجعه على طول
    $menuTree = \Cache::remember('Menu', config('cache.ttl'), function () {
        return Menu::getTree()->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'url' => $item->url,
                'type' => $item->type,
                'icon' => $item->icon,
                'target' => $item->target,
                'children' => $item->children->map(function ($child) {
                    return [
                        'title' => $child->title,
                        'url' => $child->url,
                        'icon' => $child->icon,
                        'link_target' => $child->target,
                    ];
                }),
            ];
        });
    });

    $end = microtime(true);
    $executionTime = $end - $start;

    \Log::info("MenuTree execution time: {$executionTime} seconds");
    return response()->json($menuTree);
    return response()->json([
        'execution_time' => $executionTime,
        'data' => $menuTree
    ]);
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
