<?php
namespace Amerhendy\Amer\App\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use AssetManager;
use App\Http\Controllers\Controller;

use Amerhendy\Amer\App\Helpers\AmerHelper\{models,arabic,EncryptionHelper,amerModelsHelpers,responses,controllers,arraytrait,bladetrait};
class AmerHelper
{
    use models;
    use arabic,EncryptionHelper,amerModelsHelpers,responses,controllers,arraytrait,bladetrait;
    static $arabicNumbers=[
        'standard' => array("0","1","2","3","4","5","6","7","8","9"),
        'eastern_arabic_symbols' => array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩")
    ];
    public static $currentClass;
    public static $routeMethod;
    public static $callClass;
    public static $allmodels;
    public static $RouteName;
    public function __construct($modelPath=null,$className=null) {
        $amermodels=[];
        $package_path=[];
        $package_path[]=app_path('Models');
        foreach (config('Amer') as $key => $value) {
            if(Arr::hasAny($value,'package_path')){
                $apppath=Arr::get($value,'package_path');
                $apppath=Str::of($apppath)->finish('\\');
                $apppath=Str::of($apppath)->replace('/','\\');
                $apppath=$apppath.'App\Models';
                if(File::exists($apppath)){
                    $package_path[]=$apppath;
                }
            }
        }
        self::$modelPath=array_unique($package_path);
        if(isset($modelPath)){self::$modelPath[]=$modelPath;}
        self::$modelPath=array_unique(self::$modelPath);

        self::$loaded = [];
        $route = Route::currentRouteName(); self::$RouteName=$route;
        //dd($route);
        if (stripos($route, 'fetch') !== false) {
            self::$currentClass=Route::current()->parameters()['class'] ?? '';
        }else{
            $methods=['show','update','edit','index','create','store'];
            foreach($methods as $cls){
                if (stripos($route, $cls) !== false) {
                    self::$routeMethod= $cls;
                }
            }
        }
        $allmoedls=self::getModels();
        //dd($allmoedls);
        $myClassessNames=[];
        foreach($allmoedls as $a=>$b){
            $myClassessNames[]=$b['className'];
            if(self::$currentClass == null){
                if (stripos($route, $b['className']) !== false) {
                    if(isset($b['classPath'])){
                        $clsName=$b['classPath'].'\\'.$b['className'];
                        self::$callClass=new $clsName();
                    }
                }
            }else{
                if (self::$currentClass == $b['className']) {
                    //dd(self::$currentClass,$b['callLink']);
                    $clsName=$b['callLink'];
                    self::$callClass=new $clsName();
                }
            }
        }
        if(self::$currentClass == null){
            foreach($myClassessNames as $cls){
                if (stripos($route, $cls) !== false) {
                    self::$currentClass= $cls;
                }
            }
        }

    }





    /**
     * startsWith
     *
     * @param  mixed $string
     * @param  mixed $startString
     * @return void
     */
    public static function startsWith($string, $startString) {
        return \Str::startsWith($string,$startString);
    }

    /**
     * old_empty_or_null
     *
     * @param  mixed $key
     * @param  mixed $empty_value
     * @return void
     */
    public static function old_empty_or_null($key, $empty_value = '')
    {
        $key = square_brackets_to_dots($key);
        $old_inputs = session()->getOldInput();

        // if the input name is present in the old inputs we need to return earlier and not in a coalescing chain
        // otherwise `null` aka empty will not pass the condition and the field value would be returned.
        if (\Arr::has($old_inputs, $key)) {
            return \Arr::get($old_inputs, $key) ?? $empty_value;
        }

        return null;
    }
    public static function get_old($class,$id){
        $modelinfo=publicController::loadmodels($class);
        $fields=self::fields($class);
        $fields=self::FieldLoadOnce(self::fields($class));

        $ifInDB=[];$ifISRel=[];$ifInSideRel=[];
        $mainclass=self::callClass($class);
        $mainclass=$mainclass->where('id',$id);
        $maindata=$mainclass->get()->toArray();
        if(!count($maindata)){return false;}
        foreach($fields as $a=>$b){
            if(isset($b['entity'])){
                $fieldname=$b['entity'];
            }else{
                $fieldname=$b['name'];
            }
            $ifInDBREQ = Arr::where($modelinfo['DB']['column'], function ($value, $key) use($fieldname){
                return strtolower($value) == strtolower($fieldname);
            });
            $ifISRelBREQ = Arr::where($modelinfo['relations'], function ($value, $key) use($fieldname){
                return strtolower($key) == strtolower($fieldname);
            });
            $ifInSideRelBREQ = Arr::where($modelinfo['relations'], function ($value, $key) use($fieldname){
                foreach($value as $a=>$b){
                    return strtolower($b) == strtolower($fieldname);
                }
                //return strtolower($value) == strtolower($fieldname);
            });
            if(count($ifInDBREQ)){
                foreach($ifInDBREQ as $item){$ifInDB[]=$item;}
            }
            if(count($ifISRelBREQ)){
                foreach($ifISRelBREQ as $key=>$item){
                    $ifISRel[$key]=$item;
                }

            }
            if(count($ifInSideRelBREQ)){
                foreach($ifInSideRelBREQ as $ors=>$rer){
                    $mainclass=$mainclass->whereHas($ors);
                    if(isset($b['dependencies'])){


                        $datasource=$b['data_source'];
                        foreach($b['dependencies'] as $a=>$b){
                            $arr[]=[
                                'field'=>$b,
                                'val'=>'1',
                            ];
                        }

                        parse_str(Arr::query($arr), $output);
                        //$request['dependencies']=$output;
                        //dd($request->all());

                    }
                }
            }
        }
        if(count($ifInDB)){
            foreach($fields as $a=>$b){
                if(in_array($b['name'],$ifInDB)){
                    $fields[$a]['value']=$maindata[0][$b['name']];
                }
            }
        }
        if(count($ifISRel)){
            $relnames=array_keys($ifISRel);
            $mainclass=$mainclass->with($relnames);
            $mainclass=$mainclass->get();
            foreach($fields as $a=>$b){
                if(isset($b['entity'])){
                    $entity=$b['entity'];
                    $fields[$a]['value']=$mainclass[0]->$entity;
                }
            }
        }

        return $fields;
    }
    public static function getAttrKeysForForm($data,$target='all',$fill=null){
        $res=[];
        if(!is_array($data)){$data=json_decode($data,1,512);}
        if(!in_array('id',$data)){$data[]='id';}
        if($target == 'query'){
            foreach($data as $a=>$b){
                if(is_numeric($a)){
                    if(is_array($b)){
                        $keys=array_keys($b);
                        foreach($b as $c=>$d){
                            if(is_array($d)){
                                $res[]=$c;
                            }else{
                                $res[]=$d;
                            }
                        }
                    }else{
                        $res[]=$b;
                    }

                }
            }
        }
        if($target == 'fill'){
            $attrs=self::getAttrKeysForForm($data,'query');
                foreach($fill as $a=>$b){
                    if(in_array($a,$attrs)){
                        $ids = array_map(
                            function ($ar)use($a) {
                                if($a !== 'id'){
                                    if(!is_array($a)){
                                        if(isset($ar[$a]['prefix'])){
                                            return $ar[$a];
                                        }
                                    }else{
                                        return $ar[$a];
                                    }

                                }
                        }
                            , $data);
                        if(isset($ids[0]['prefix'])){
                            $b=$ids[0]['prefix'].$b;
                        }
                        if(isset($ids[0]['suffix'])){
                            $b.=$ids[0]['suffix'];
                        }
                        $res[$a]=$b;

                    }
                }

        }
        return $res;
    }
    public static function formdependencies_inval($data,$currentClass){
        $Inputs=[]; $rels=[];$gets=[];$where=[];
        $gets[]='id';
        $result=[];
        $attributcols=self::getAttrKeysForForm($data['attributes'],'query');
        $homemodel=self::callClass($data['homemodel']);
        $wantedmodel=self::callClass($data['wanted']);
        $home=$data['homemodel'];
        $wanted=$data['wanted'];
        $homerelationdata=self::relationData($homemodel);
        $wantedrelationdata=self::relationData($wantedmodel);
        $wanteddata=[];
        // الخطأ بسبب نوع العلاقة
        if(array_key_exists($wanted,$homerelationdata)){
            $res=$homemodel->query()->with([$data['wanted']=>function($q)use($wanted,$attributcols){
                if(!is_array($attributcols)){
                    $q->select($wanted.'.id',$wanted.'.'.$attributcols);
                }
            }])->where('id',$data['val'])->get()->toArray();
            $res=self::getdatafromrelation($res,$wanted);
            $wanteddata=array_map(function($n)use($attributcols){
                $data=[];
                foreach($attributcols as $a=>$b){
                    if(in_array($b,array_keys($n))){
                        $data[$b]=$n[$b];
                    }
                }
                return $data;
            },$wanteddata);
        }elseif(array_key_exists($home,$wantedrelationdata)){
            $attrkeys=self::getAttrKeysForForm($data['attributes'],'query');
            $res=$wantedmodel->where($wantedrelationdata[$home]['forignkey'],$data['val'])->get($attrkeys)->toArray();
            foreach($res as $a=>$b){
                unset($res[$a]['created_at']);unset($res[$a]['updated_at']);unset($res[$a]['deleted_at']);
                foreach($b as $c=>$d){
                    $res[$a][$c]=$d;
                }
            }
            foreach($res as $a=>$b){
                $attr=self::getAttrKeysForForm($data['attributes'],'fill',$b);
                $wanteddata[$a]=$attr;
            }
        }
        return $wanteddata;
    }
    public static function isJson($string) {
        if(gettype($string) !== 'string'){return false;}
        try {
            json_decode($string);
            return true;
        } catch (\Throwable $th) {
            return json_last_error() === JSON_ERROR_NONE;
        }
    }
    public static function add_directory( $directory, $cache_path ) {

        if (!File::exists( $cache_path.'/'.$directory)) {
            File::makeDirectory($cache_path . '/' . $directory, 0755, true);
        }
    }
    public static function betweenTwoDates($date,$start,$end){
        $first= \Carbon\Carbon::parse($start);
        $second= \Carbon\Carbon::parse($end);
        return \Carbon\Carbon::parse($date)->between($first,$second);
    }
}
