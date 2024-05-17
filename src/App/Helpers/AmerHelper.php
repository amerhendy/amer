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
class AmerHelper 
{
    static $arabicNumbers=[
        'standard' => array("0","1","2","3","4","5","6","7","8","9"),
        'eastern_arabic_symbols' => array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩")
    ];
    public static $loaded=[];
    public static $modelPath=[];
    public static $currentClass;
    public static $routeMethod;
    public static $callClass;
    public static $allmodels;
    public static $RouteName;
    private static $secretKey ,$secretIv,$encryptMethod;
    public function __construct($modelPath=null,$className=null,$secretKey=null,$secretIv=null,$encryptMethod=null) {
        if(is_null($secretKey)){$secretKey=config('Amer.amer.SecretKey','Amer');}
        if(is_null($secretIv)){$secretIv=config('app.url',\URL::to('/'));}
        if(is_null($encryptMethod)){$encryptMethod=config('app.cipher','AES-256-CBC');}
        self::$secretKey=$secretKey;self::$secretIv=$secretIv;self::$encryptMethod=$encryptMethod;
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
        if (stripos($route, 'api') !== false) {
            self::$currentClass=Route::current()->parameters()['class'];
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
    public static function createShortUrl($url,$minutes=null){
        if(\Str::contains($url,"?expires=")){
            //search for
            $searchfor = Str::of($url)->afterLast('/');
            $searchfor = Str::of($searchfor)->before('?');
        }else{
            $searchfor = Str::of($url)->afterLast('/');
        }
        $find=\Amerhendy\Amer\App\Models\ShortUrls::where('OriginalUrls','LIKE',"%".$searchfor."%")->first();
        $create=true;
        if(!$find){
            $create=true;
        }else{
            if($find->time == null){
                $create=false;
                return $find->ShortenUrls;
            }else{
                $create=false;
                $endDate=$find->created_at->addMinutes($find->time);
                $date = new \Carbon\Carbon();
                $compare=$date->now()->gt($endDate);
                if($compare == true){
                    //remove then create
                    $find->forceDelete();
                    $create=true;
                }else{
                    return $find->ShortenUrls;
                }
            }
        }
        if($create == true){
            $new=new \Amerhendy\Amer\App\Models\ShortUrls();
            $new->OriginalUrls=$url;
            $new->time=$minutes;
            $new->ShortenUrls=substr(md5((string) \Str::uuid()),0,6);
            $new->created_at=now();
            $new->save();
            return $new->ShortenUrls;
        }
        //original,time
    }
    public static function findController($path=null,$name=null){
        $allprojectfiles=self::array_flatten(self::allprojectfiles());
        $allprojectfiles=\Arr::where($allprojectfiles,function($v,$k){
            return \Str::contains($v,'ontroller');
        });
        foreach($allprojectfiles as $a=>$b){
            $filename=$b;
                $fp = fopen($filename, 'r');
                    $className = $buffer = '';
                        $i = 0;
                        
                        while (!$className) {
                            if (feof($fp)) break;
                            $buffer .= fread($fp, 512);
                            $tokens = token_get_all($buffer);
                            if (strpos($buffer, '{') === false) continue;
                            for ($i;$i<count($tokens);$i++) {
                                if ($tokens[$i][0] === T_NAMESPACE) {
                                    $namespace=$tokens[$i+2][1];
                                }
                                if ($tokens[$i][0] === T_CLASS) {
                                    for ($j=$i+1;$j<count($tokens);$j++) {
                                        if ($tokens[$j] === '{') {
                                            if(!isset($tokens[$i+2][1])){
                                                continue;
                                            }
                                            $className = $tokens[$i+2][1];
                                            if(!isset($tokens[$i+4][1]))continue;
                                            if($tokens[$i+4][1] !== 'extends') continue;
                                            if(!in_array($tokens[$i+6][1],['AmerController','Controller'])) continue;
                                            
                                        }
                                    }

                                }
                                if(isset($namespace) && isset($className) && $className !== ''){
                                    $fullclassname[]=$namespace.'\\'.$className;
                                }
                            }
                        }
        }
        $fullclassname=collect($fullclassname);
        return($fullclassname->unique()->toArray());
    }
    private static function allprojectfiles($base=null){
        if($base == null){$base=base_path();}
        $results = scandir($base);
        $blockedfiles=['.','..','.env','composer.json','composer.lock','README.md'];
        $files=[];
            foreach ($results as $result) {
                if (in_array($result,$blockedfiles) OR self::startsWith($result,'.') OR Str::contains($result,'.md') OR Str::contains($result,'.js') OR Str::contains($result,'.css') OR Str::contains($result,'.blade.php' OR Str::contains($result,'.tmp') OR Str::contains($result,'.xml'))) continue;
                $filename=$base.'/'.$result;
                if(\File::isDirectory($filename)){$files[]=self::allprojectfiles($filename);}
                if(\Str::endsWith($filename,'.php')){
                    $files[]=$filename;
                }
            }
            return $files;
    }
    static function array_flatten($array)
    {
    $result = [];
    foreach ($array as $element) {
        if (is_array($element)) {
        $result = array_merge($result, self::array_flatten($element));
        } else {
        $result[] = $element;
        }
    }
    return $result;
    }
    static function arrayFlattenWKey($array,$prefix="")
    {
        $result = array();
        foreach($array as $key=>$value) {
            if(is_array($value)) {
                $result = $result + self::arrayFlattenWKey($value);
            }
            else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }
    static function arrayFlatten($array){
        $result = [];
        foreach ($array as $key => $value) {
            if(is_array($value)) {
                $result[]=self::arrayFlattenWKey($value);
            }
        }
        return $result;
    }
    public static function startsWith($string, $startString) { 
        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString); 
      } 
    public static function modelexists($model){
        return (class_exists($model));
    }
    public static function getModels($modelPath=null){
        if(empty($modelPath)){$modelPath=self::$modelPath;}
        if(!is_array($modelPath)){
            $modelPath=[$modelPath];
        }
        $out = [];
        foreach($modelPath as $a=>$b){
            $path=$b;
            $results = scandir($path);
            foreach ($results as $result) {
                if ($result === '.' or $result === '..') continue;
                $filename = $result;
                $fpath=$path.'\\'.$filename;
                if (is_dir($fpath)) {
                    $out = array_merge($out, self::getModels($path.'\\'.$filename));
                }else{
                    $classPath=str_replace(base_path(),'',$path);
                    $fp = fopen($path.'\\'   .$filename, 'r');
                    $className = $buffer = '';
                        $i = 0;
                        while (!$className) {
                            if (feof($fp)) break;
                            $buffer .= fread($fp, 512);
                            $tokens = token_get_all($buffer);
                            if (strpos($buffer, '{') === false) continue;
                            for ($i;$i<count($tokens);$i++) {
                                if ($tokens[$i][0] === T_CLASS || $tokens[$i][0] === T_TRAIT) {
                                    for ($j=$i+1;$j<count($tokens);$j++) {
                                        if ($tokens[$j] === '{') {
                                            if(!isset($tokens[$i+2][1])){
                                                dd($filename);
                                            }
                                            $className = $tokens[$i+2][1];
                                        }
                                    }
                                }
                            }
                        }
                        if(str_contains($classPath,'vendor')){
                            $nameSpace=str_replace("/","\\",$classPath);
                            $nameSpace=str_replace("vendor","",$nameSpace);
                            $nameSpace=str_replace("src","",$nameSpace);
                            $nameSpace=str_replace("\\\\","\\",$nameSpace);$nameSpace=str_replace("\\\\","\\",$nameSpace);$nameSpace=str_replace("\\\\","\\",$nameSpace);
                        }elseif((str_contains($classPath,'app')) || str_contains($classPath,'App')){
                            $nameSpace=str_replace("app","App",$classPath);
                            $nameSpace=str_replace("/","\\",$nameSpace);
                            $nameSpace=str_replace("/Models","/models",$nameSpace);
                            
                        }else{
                            dd($filename,$classPath);
                        }
                        $outfile=substr($filename,0,-4);
                        $outpath=base_path().'\\'.$classPath.'\\'.$outfile;
                        $outpath=Str::replace('\\\\','\\',$outpath);
                        $callink=$nameSpace.'\\'.$className;
                    $out[]=[
                        'nameSpace'=>$nameSpace,
                        'path'=>$outpath,
                        'filename'=>$outfile,
                        'className'=>$className,
                        'callLink'=>$callink
                    ];
                }
            }
        }
        return $out;
    }

    public static function getModels2($modelPath=null){
        $out = [];
        if($modelPath == null){
            if(self::$modelPath == null){
                self::$modelPath = app_path() . "\\Models";
            }
            $path=self::$modelPath;
        }else{
            $path=$modelPath;
        }
        
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;
            $filename = $result;
            if (is_dir($path.'\\'.$filename)) {
                $out = array_merge($out, self::getModels($path.'\\'.$filename));
            }else{
                $classPath=str_replace(base_path(),'',$path);
                $fp = fopen($path.'\\'   .$filename, 'r');
                $className = $buffer = '';
                    $i = 0;
                    while (!$className) {
                        if (feof($fp)) break;
    
                        $buffer .= fread($fp, 512);
                        $tokens = token_get_all($buffer);
    
                        if (strpos($buffer, '{') === false) continue;
    
                        for (;$i<count($tokens);$i++) {
                            if ($tokens[$i][0] === T_CLASS) {
                                for ($j=$i+1;$j<count($tokens);$j++) {
                                    if ($tokens[$j] === '{') {
                                        $className = $tokens[$i+2][1];
                                    }
                                }
                            }
                        }
                    }
                    if(str_contains($classPath,'vendor')){
                        $nameSpace=str_replace("/vendor","",$classPath);
                        $nameSpace=str_replace("/src","",$nameSpace);
                        $nameSpace=str_replace("/","\\",$nameSpace);
                    }elseif(str_contains($classPath,'app')){
                        $nameSpace=str_replace("app","App",$classPath);
                        $nameSpace=str_replace("/Models","/models",$nameSpace);
                        $nameSpace=str_replace("/","\\",$nameSpace);
                    }
                    $outfile=substr($filename,0,-4);
                    $outpath=base_path().'\\'.$classPath.'\\'.$outfile;
                    $callink=$nameSpace.'\\'.$className;
                $out[]=[
                    'nameSpace'=>$nameSpace,
                    'path'=>$outpath,
                    'filename'=>$outfile,
                    'className'=>$className,
                    'callLink'=>$callink
                ];
            }
        }
        return $out;
    }
    
    public static function loadmodels($modelName=null){
        $models=self::getModels();
        
        foreach($models as $a=>$b){
            if(($b['className'] == 'User') || ($b['className'] == 'Role') || ($b['className'] == 'Permission') || ($b['className'] == 'Admin') || ($b['className'] == 'menu')){
                unset($models[$a]);
            }
        }
        foreach($models as $a=>$b){
            $model=$b['callLink'];
            $model=new $model();
            $models[$a]['DB']['Name']=$model->getTable();
            $models[$a]['DB']['column']=DB::getSchemaBuilder()->getColumnListing($model->getTable());
            $models[$a]['relations']=self::relationData($model);
        }
        if($modelName !== null){
            $filtered = Arr::where($models, function ($value, $key) use($modelName){
                return $value['className'] == $modelName;
            });
            foreach($filtered as $fi){
                $filtered=$fi;
            }
            return $filtered;
        }
        return $models;
    }

    /**
     * getAllRelations
     *
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param string $heritage
     * 
     * @return [type]
     */
    /**
     * getAllRelations
     *
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param string $heritage
     * 
     * @return [type]
     */
    public static function getAllRelations(\Illuminate\Database\Eloquent\Model $model = null, $heritage = 'all')
    {
        $model = $model ?: $this;
        $modelName = get_class($model);
        $types = ['children' => 'Has', 'parents' => 'Belongs', 'all' => ''];
        $heritage = in_array($heritage, array_keys($types)) ? $heritage : 'all';
        if (\Illuminate\Support\Facades\Cache::has($modelName."_{$heritage}_relations")) {
            $res= \Illuminate\Support\Facades\Cache::get($modelName."_{$heritage}_relations"); 
        }
        $reflectionClass = new \ReflectionClass($model);
        $traits = $reflectionClass->getTraits();    // Use this to omit trait methods
        $traitMethodNames = [];
        foreach ($traits as $name => $trait) {
            $traitMethods = $trait->getMethods();
            foreach ($traitMethods as $traitMethod) {
                $traitMethodNames[] = $traitMethod->getName();
            }
        }
        // Checking the return value actually requires executing the method.  So use this to avoid infinite recursion.
        $currentMethod = collect(explode('::', __METHOD__))->last();
        $filter = $types[$heritage];
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);  // The method must be public
        $methods = collect($methods)->filter(function ($method) use ($modelName, $traitMethodNames, $currentMethod,$model) {
        $methodName = $method->getName();
            if (!in_array($methodName, $traitMethodNames)   //The method must not originate in a trait
                && strpos($methodName, '__') !== 0  //It must not be a magic method
                && $method->class === $modelName    //It must be in the self scope and not inherited
                && !$method->isStatic() //It must be in the this scope and not static
                && $methodName != $currentMethod    //It must not be an override of this one
            ) {
        ///////////////////////
        $relation = $model->$methodName();
        //dd($methodName);
        if (is_subclass_of($relation, \Illuminate\Database\Eloquent\Relations\Relation::class)) {
                //if($methodName !=='getTable'){
                    $parameters = (new \ReflectionMethod($modelName, $methodName))->getParameters();
                
                    //if(count($parameters) == 0){return false;}
                    return collect($parameters)->filter(function ($parameter) {
                        return !$parameter->isOptional();   // The method must have no required parameters
                    })->isEmpty();  // If required parameters exist, this will be false and omit this method
                //}
        }
        ///////////////////////
        
            }
            return false;
        })->mapWithKeys(function ($method) use ($model, $filter) {
            $methodName = $method->getName();
            $relation = $model->$methodName();  //Must return a Relation child. This is why we only want to do this once
            if (is_subclass_of($relation, \Illuminate\Database\Eloquent\Relations\Relation::class)) {
                $type = (new \ReflectionClass($relation))->getShortName();  //If relation is of the desired heritage
                if (!$filter || strpos($type, $filter) === 0) {
                    
                    return [$methodName => get_class($relation->getRelated())]; // ['relationName'=>'relatedModelClass']
                }
                
            }
            return false;   // Remove elements reflecting methods that do not have the desired return type
        })->toArray();

        \Illuminate\Support\Facades\Cache::forever($modelName."_{$heritage}_relations", $methods);

        return $methods;
    }
    public static function relationData($class){
        $all=self::getAllRelations($class);
        $lastdata=[];
        foreach($all as $a=>$b){
            $forignkey='';
            $localkey='';
            $table='';
            $targetclass=$b;
            $oReflectionClass = new \ReflectionClass($class);
            $method = $oReflectionClass->getMethod($a);
            $type = get_class($method->invoke($class));
            $type=explode('\\',$type);
            $type=$type[count($type)-1];
            $rel=$class->$a();
            switch ($type) {
                case 'BelongsTo':
                    $forignkey=$rel->getForeignKeyName();
                    $localkey=$class->$a()-> getOwnerKeyName() ;
                    $table=$class->$a()-> getRelated()->getTable();
                    break;
                case 'BelongsToMany':
                        $forignkey=$class->$a()->  getRelatedPivotKeyName() ;
                        $localkey=$class->$a()-> getForeignPivotKeyName() ;
                        $table=$class->$a()->getTable();
                        $relatedTable=$class->$a()->getRelated()->getTable();
                    break;
                case 'HasMany':
                    # code...
                    break;
                case 'HasManyThrough':
                    # code...
                    break;
                case 'HasOne':
                    # code...
                    break;
                case 'HasOneOrMany':
                    # code...
                    break;
                case 'HasOneThrough':
                    # code...
                    break;
                case 'MorphMany':
                    # code...
                    break;
                case 'MorphOne':
                    # code...
                    break;
                case 'MorphOneOrMany':
                    # code...
                    break;
                case 'MorphPivot':
                    # code...
                    break;
                case 'MorphTo':
                    # code...
                    break;
                case 'MorphToMany':
                    # code...
                    break;
                case 'Pivot':
                    # code...
                    break;
                case 'Relation':
                    # code...
                    break;
            }
            if($a == $table){unset($all[$a]);}
            if(isset($forignkey)){$lastdata[$a]['forignKey']=$forignkey;}
            if(isset($localkey)){$lastdata[$a]['localkey']=$localkey;}
            if(isset($table)){$lastdata[$a]['table']=$table;}
            if(isset($type)){$lastdata[$a]['type']=$type;}
            if(isset($relatedTable)){$lastdata[$a]['relatedTable']=$relatedTable;}
            $lastdata[$a]['target_class']=$b;
            //$all[$a]=['forignkey'=>$forignkey,'localkey'=>$localkey,'table'=>$table,'type'=>$type,'target_class'=>$b];
        }
        return $lastdata;
    }
    public static function fields($class){
        //fields types
        /*
        you can add 'hint'      =>'' to all type 
        you can add 'placeholder'      =>'' to all type 
            text
                    ['type'=>'text','name'=>'text','label'=>'الكفاءة']
            select2
                    [
                        'name'=>'JobTitle_id',
                        'label'=>'المسمى',
                        'type'=>'select2',
                        'model'=>'App\Models\Mosama\Mosama_JobTitles',
                        'attribute'=>'text',
                    ],
            select2_multiple
                    [
                        'name'=>'Mosama_Groups', // relation name of the model
                        'label'=>'المجموعات',
                        'type'=>'select2_multiple',
                        'attribute'=>'text', // column i want user see
                        'pivot'=>true,
                        'model'=>'\App\Models\Mosama\Mosama_Groups', // model i want to get data
                    ],
            select_from_array
                    [   
                        'name'        => 'type',
                        'label'       => "النوع",
                        'type'        => 'select_from_array',
                        'options'     => ['in' => 'اتصالات داخلية', 'out' => 'اتصالات خارجية'],
                        'allows_null' => false,
                        'default'     => 'in',
                    ],
            select2_from_ajax_multiple
                    [   // n-n relationship
                        'label'       => "Cities", // Table column heading
                        'type'        => "select2_from_ajax_multiple",
                        'name'        => 'cities', // a unique identifier (usually the method that defines the relationship in your Model)
                        'entity'      => 'cities', // the method that defines the relationship in your Model
                        'attribute'   => "name", // foreign key attribute that is shown to user
                        'data_source' => url("api/city"), // url to controller search function (with /{id} should return model)
                        'pivot'       => true, // on create&update, do you need to add/delete pivot table entries?

                        // OPTIONAL
                        'delay'                      => 500, // the minimum amount of time between ajax requests when searching in the field
                        'model'                      => "App\Models\City", // foreign key model
                        'placeholder'                => "Select a city", // placeholder for the select
                        'minimum_input_length'       => 2, // minimum characters to type before querying results
                        // 'method'                  => 'POST', // optional - HTTP method to use for the AJAX call (GET, POST)
                        // 'include_all_form_fields' => false, // optional - only send the current field through AJAX (for a smaller payload if you're not using multiple chained select2s)
                    ],
         */
        $class=self::$currentClass;
        if($class == null){
            return view('errors.layout',['error_number'=>404,'error_message'=>'NOMCV']);
        }
        
        $class=self::$callClass;
        $fields=$class::$fileds;
        foreach($fields as $a=>$b){
            if(
                ($b['type']== 'relationship')){
                    $oReflectionClass = new \ReflectionClass($class);
                    $method = $oReflectionClass->getMethod($b['entity']);
                    $type = get_class($method->invoke($class));
                    $type=explode('\\',$type);
                    $type=$type[count($type)-1];
                    if($type == 'HasOne'){$fields[$a]['multiple']=false;}
                    if($type == 'BelongsToMany'){$fields[$a]['multiple']=true;}
                }
            if(
                ($b['type']== 'browse_multiple') || 
                ($b['type']== 'browse') || 
                (strpos($b['type'],'upload')) || 
                (strpos($b['type'],'file')) || 
                (array_key_exists('path',$b)) || 
                (array_key_exists('disk',$b))
                ){
                    $fields[$a]=self::setstoragefield($b);
            }
        }
        return $fields;
    }   
    private static function setstoragefield($field){
        if(!isset($field['disk'])){$disk='';}else{$disk=$field['disk'];}
        if(!isset($field['path'])){$path=$path=Auth::user()->name;}else{$path=$field['path'];}
            if($disk == ''){
                $disk=config('elfinder.disks')[0];
            }else{
                if(!in_array($disk,config('elfinder.disks'))){$disk=config('elfinder.disks')[0];}
                
                //dd(Connector->getResponse());
            }
            
            if($path == ''){
                $path=Auth::user()->name;
            }
            
            if(!Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->makeDirectory($path);
            }
            $field['path']=$path;$field['disk']=$disk;
            
        return $field;
    }
    public static function FieldLoadOnce($fields)
    {
        $fieldtype=[
                    'address'=>[],
                    'custom_html'=>[],
                    'color'=>[],
                    'date'=>[],
                    'datetime'=>[],
                    'email'=>[],
                    'enum'=>[],
                    'hidden'=>[],
                    'month'=>[],
                    'number'=>[],
                    'password'=>[],
                    'range'=>[],
                    'select'=>[],
                    'select_from_array'=>[],
                    'select_grouped'=>[],
                    'select_multiple'=>[],
                    'text'=>[],
                    'textarea'=>[],
                    'time'=>[],
                    'url'=>[],
                    'view'=>[],
                    'week'=>[],
                    'checkbox'=>['script'=>['bpFieldInitCheckbox']],
                    'boolean'=>['script'=>['bpFieldInitCheckbox']],
                    'checklist'=>['script'=>['bpFieldInitChecklist']],
                    'checklist_dependency'=>['script'=>['bpFieldInitChecklistDependencyElement']],
                    'radio'=>['script'=>['bpFieldInitRadioElement']],
                    'switch'=>['script'=>['bpFieldInitSwitchScript']],
                    'summernote'=>[
                        'css'=>['packages/summernote/dist/summernote-bs4.css'],
                        'style'=>['summernoteCss'],
                        'js'=>['packages/summernote/dist/summernote-bs4.min.js']
                    ],
                    'upload'=>[
                        'style'=>['upload_field_styles'],
                        'script'=>['bpFieldInitUploadElement']
                    ],
                    'upload_multiple'=>[
                        'style'=>['upload_field_styles'],
                        'script'=>['bpFieldInitUploadMultipleElement']
                    ],
                    'address_algolia'=>[
                        'js'=>['places.min.js'],
                        'style'=>['address_algoria_style'],
                        'script'=>['bpFieldInitAddressAlgoliaElement']
                    ],
                    'address_google'=>[
                        'style'=>['address_algoria_style'],
                        'script'=>['bpFieldInitAddressGoogleElement'],
                        'js'=>['googlemaps.js']
                    ],
                    'base64_image'=>[
                        'js'=>['cropper.js'],
                        'css'=>['cropper.css'],
                        'style'=>['base64style'],
                        'script'=>['bpFieldInitBase64CropperImageElement']
                    ],
                    'browse_multiple'=>[
                        'js'=>['jquery-ui.js','jquerycolorbox.js'],
                        'css'=>['colorbox.css'],
                        'style'=>['cbox_style'],
                        'script'=>['bpFieldInitBrowseMultipleElement']
                    ],
                    'browse'=>[
                        'js'=>['jquerycolorbox.js'],
                        'css'=>['colorbox.css'],
                        'style'=>['cbox_style'],
                        'script'=>['bpFieldInitBrowseElement']
                    ],
                    'ckeditor'=>[
                        'js'=>['ckeditor.js'],
                        'script'=>['bpFieldInitCKEditorElement']
                    ],
                    'color_picker'=>[
                        'js'=>['colorbicker.js'],
                        'css'=>['colorbicker.css'],
                        'style'=>['colorbickerstyle'],
                        'script'=>['bpFieldInitColorPickerElement']
                    ],
                    'date_picker'=>[
                        'js'=>['datepicker.js'],
                        'css'=>['datepicker3.css'],
                        'script'=>['bpFieldInitDatePickerElement']
                    ],
                    'date_range'=>[
                        'js'=>['daterangepicker.js','momentwithlocales.js'],
                        'css'=>['daterangepicker.css'],
                        'script'=>['bpFieldInitDateRangeElement']
                    ],
                    'datetime_picker'=>[
                        'css'=>['datepicker3.css','datetimepicker.css'],
                        'js'=>['moment.js','datetimepicker.js','datetimepickerlocal.js'],
                        'script'=>['bpFieldInitDateTimePickerElement']
                    ],
                    'easymde'=>[
                        'js'=>['easymde.js'],
                        'css'=>['easymde.css'],
                        'style'=>['easymde.style'],
                        'script'=>['bpFieldInitEasyMdeElement']
                    ],
                    'icon_picker'=>[],
                    'image'=>[],
                    'page_or_link'=>[],
                    'relationship'=>[],
                    'repeatable'=>[],
                    'select_and_order'=>[],
                    'select2'=>[],
                    'select2_from_ajax'=>[],
                    'select2_from_ajax_multiple'=>[],
                    'select2_from_array'=>[],
                    'select2_grouped'=>[],
                    'select2_multiple'=>[],
                    'select2_nested'=>[],
                    'simplemde'=>[],
                    'table'=>[],
                    'tinymce'=>['js'=>['packages/tinymce/tinymce.min.js','bpFieldInitTinyMceElement','elFinderBrowser']],
                    'video'=>[],
                    'wysiwyg'=>['js'=>['packages/ckeditor/ckeditor.js','packages/ckeditor/adapters/jquery.js','bpFieldInitCKEditorElement']],
                    'Mosama_job_a'=>['select2']
        ];
        $asd=Arr::where($fields,function($value,$key)use($fieldtype){
            dd($fieldtype);
            return $value['type'];
        });
        dd($asd);
        $cols=$fields;
        $ss=[];
        for($i=0;$i<=count($cols)-1;$i++){
            if(array_key_exists($cols[$i]['type'],$fieldtype)){
                $ss[$i]=$fieldtype[$cols[$i]['type']];
            }else{
                $ss[$i]=$cols[$i]['type'];
            }
        }
        $uniq=array_unique($ss);
        for($i=0;$i<=count($cols)-1;$i++){
            if(array_key_exists($i,$uniq)){
                $cols[$i]['jscss']=True;
                //print $i;
            }else{
                $cols[$i]['jscss']=False;
            }
        }
        //dd($cols);
        dd($cols);
        return $cols;
    }
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
    public static function callclass($name,$wanted='null'){ 
     $models=self::$allmodels;
     foreach($models as $model){
        if($model['className'] == $name){
            $class= $model['classPath'].'\\'.$model['className'];
            if($wanted == 'link'){return '\\'.$class;}
            return new $class();
        }
        
     }
    }
    
    public static function getAttrKeysForForm($data,$target='all',$fill=null){
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
    public static function fetchfromrelations(\Illuminate\Database\Eloquent\Model $model,$reqRelName){
        //dd($reqRelName);
        //relationData
        //dd(self::relationData($model));
        //dd(self::getAllRelations($model));
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
    public static function removeCreatedUpdatedDeleted($data){
        foreach($data as $a=>$b){
            unset($data[$a]['created_at']);unset($data[$a]['updated_at']);unset($data[$a]['deleted_at']);
        }
        return $data;
    }
    public static function convertrelationname($data){
        if(count($data)){
            foreach($data as $a=>$b){
                unset($data[$a]['created_at']);unset($data[$a]['updated_at']);unset($data[$a]['deleted_at']);
                //$array= $b->toArray();
                //$keys=array_keys($array);
                //dd($b->toArray());
                foreach($b as $c=>$d){
                    
                    
                    
                    if(str_contains($c,'__')){
                        //$data[$a][str_replace("__","_",$c)]=$d;
                      
                        //unset($data[$a][$c]);
                    }
                }
            }
        }
        return $data;
    }
    public static function getdatafromrelation($data,$relname){
        //$data=self::convertrelationname($data);
        $wanteddata=[];
        /*
        foreach($data as $a=>$b){
            $array= $b->toArray();
            $keys=array_keys($array);
            foreach($keys as $c=>$d){
                if(strtolower($d) == strtolower($relname)){
                    $wanteddata= $d;
                }
            }
        }
        //dd($wanteddata);
        foreach($wanteddata as $a=>$b){
            foreach($b as $c=>$d){
                unset($wanteddata[$a]['created_at']);unset($wanteddata[$a]['updated_at']);unset($wanteddata[$a]['deleted_at']);
                if(isset($wanteddata[$a]['pivot'])){unset($wanteddata[$a]['pivot']);}
            }
        }*/
        return $data;
        if($sourcetype == 'Array'){return $wanteddata;}
        if($sourcetype == 'object'){return collect($wanteddata);}
    }
    public static function responsedata($data_arr,$draw=null,$classcount=null,$recordsFiltered=null){
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $classcount,
            "recordsFiltered" => $recordsFiltered,
           "data" => $data_arr
         );
         return response()->json($response,200);
    }
    public static function responseError($message,$code){
        $arr=['message'=>$message];
        return response()->json($arr,$code);
    }
    public static function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
     }
     public static function delete_all_between($beginning, $end, $string) {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
          return $string;
        }
      
        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
      
        return self::delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
      }
    public static function echoCss($path,$type=null)
    {
        if (self::isLoaded($path)) {
            return;
        }
        
        self::markAsLoaded($path);
        if($type == null){$type='all';}
        echo '<link href="'.asset($path).'" rel="stylesheet" type="text/css"  media="'.$type.'"/>';
    }

    public static function echoJs($path)
    {
        $attr='';
        if(is_array($path)){
            $attr=$path[1];
            $path=$path[0];
        }
            if (self::isLoaded($path)) {
                return;
            }
            
            self::markAsLoaded($path);
            echo '<script src="'.asset($path).'" '.$attr.'></script>';
    }

    /**
     * Adds the asset to the current loaded assets.
     *
     * @param  string  $asset
     * @return void
     */
    public static function markAsLoaded($asset)
    {
        if (! self::isLoaded($asset)) {
            self::$loaded[] = $asset;
        }
    }

    /**
     * Checks if the asset is already on loaded asset list.
     *
     * @param  string  $asset
     * @return bool
     */
    public static function isLoaded($asset)
    {
        if (in_array($asset, self::$loaded)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the current loaded assets on app lifecycle.
     *
     * @return array
     */
    public function loaded()
    {
        return $this->loaded;
    }    
    public static function add_directory( $directory, $cache_path ) {

        if (!File::exists( $cache_path.'/'.$directory)) {
            File::makeDirectory($cache_path . '/' . $directory, 0755, true);
        }
    }
    public static function isHtml($string)

    {
    
      return preg_match("/<[^<]+>/",$string,$m) != 0;
    
    }
    public static function createhtmllimitstring($string){
        if(gettype($string) !== 'string'){return $string;}
        if(Str::of(trim($string))->length()<40){
            return $string;
        }
        if(self::isHtml($string)) {$htm=true;}else{$htm=false;}
        if($string != strip_tags($string)) {$clean=true;}else{$clean=false;}
        if($htm == true){
                $htmldata='<div id="fullHtml" style="display:none">'.$string.'
                                <br><span role="link" class="badge bg-primary" style="cursor:pointer;" onclick="readmore(this,\'shortdata\')">(اقرأ أقل)</span>
                                <span role="link" class="badge bg-primary" style="cursor:pointer;" onclick="readmore(this,\'cleandata\')">(اقرأ بدون تنسيق)</span>
                            </div>';
                }
        $cleandata=self::decodeHTMLEntities($string);
        
        if(Str::of($cleandata)->length()>40){
            $shortText= Str::limit($cleandata,40);
        }else{
            $shortText=$cleandata;
        }
        
        $cleandata='<div id="cleandata" style="display:none">'.$cleandata.'
                    <br><span role="link" class="badge bg-primary" style="cursor:pointer;" onclick="readmore(this,\'shortdata\')">(اقرأ أقل)</span>
                    <span role="link" class="badge bg-info" style="cursor:pointer;" onclick="readmore(this,\'fullHtml\')">(عرض كامل)</span>
                    </div>';
        $shortText= '<div id="shortdata">'.$shortText;
        if(Str::of($cleandata)->length()<40){
            $shortText.='<span role="link" class="badge bg-primary" style="cursor:pointer;" onclick="readmore(this,\'cleandata\')"><i class="fa-solid fa-broom"></i></span>';
            if($htm == true){$shortText.='<span role="link" class="badge bg-info" style="cursor:pointer;" onclick="readmore(this,\'fullHtml\')"><i class="fa fa-css3" aria-hidden="true"></i></span>';}
        }else{
            if($htm == true){$shortText.='<span role="link" class="badge bg-info" style="cursor:pointer;" onclick="readmore(this,\'fullHtml\')"><i class="fa fa-css3" aria-hidden="true"></i></span>';}
        }
        $shortText.= '</div>';
        $data='';
        if($htm == true){$data.=$htmldata;}
        $data.=$cleandata.$shortText;
        return $data;
    }
    public static function decodeHTMLEntities($string){
        if(gettype($string) == 'string'){
            $string=strip_tags($string);
            $string=trim(preg_replace('/\s\s+/', ' ', $string));
            $string=Str::squish($string);
        }
        return $string;
        
    }
    public static function get_loaded_providers($provider=null){
        $prov=app()->getLoadedProviders();
        if($provider == null){return $prov;}
        if(array_key_exists($provider,$prov)){
            return true;
        }
        return false;
    }
    public static function retunFetchValue($arr,$text){
        if(isset($arr['form'])){
            $ob=(\Arr::where($arr['form'],function($v,$k)use($text){
                return \Str::contains($v['name'],$text);
            }));
            if(count($ob)){
                $ab=[];
                foreach($ob as $a=>$b){
                    $ab[]=$b['value'];
                }
                return $ab;
            }else{
                return [];
            }
        }
    }
    public static function LandLineCode(){
        $result=\DB::table('Cities')->select('LandLineCode')->groupBy('LandLineCode')->get();
        return $result;
    }
    static function ArabicNumbersText($text){
        return str_replace(self::$arabicNumbers['standard'] , self::$arabicNumbers['eastern_arabic_symbols'] , $text);
    }
    static function ArabicDate($year,$month,$day,$hour=null,$minute=null,$am=null) {
        $newdate=new \DateTime();
        $newdate->setDate($year, $month, $day);
        $months = trans('AMER::trojan.months');
        $en_month = $newdate->format('M');
        $ar_month=trans('AMER::trojan.months.'.$en_month);
        $find = array ("Sat", "Sun", "Mon", "Tue", "Wed" , "Thu", "Fri");
        $replace = trans('AMER::trojan.days');
        $ar_day_format = $newdate->format('D');
        $ar_day = str_replace($find, $replace, $ar_day_format);
        $standard = array("0","1","2","3","4","5","6","7","8","9");
        $eastern_arabic_symbols = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
        $current_date = $ar_day.' '.trans("AMER::trojan.moafeq").' '.$newdate->format('d').' '.$ar_month.' '.$newdate->format('Y');
        $arabic_date=self::ArabicNumbersText($current_date);
        if(isset($hour)){
            $hour=23;
            if($hour > 12){
                $am='PM';
                $hour=$hour-12;
            }
            if(!isset($minute)){$minute =0;}if(!isset($am)){$am ='AM';}
            $am=trans('AMER::trojan.hour.'.$am);
            $time=\Str::replaceArray('?',[$hour,$minute,$am],trans("AMER::trojan.hourFullText"));
            $arabictime=self::ArabicNumbersText($time);
            return $arabic_date.' '.$arabictime;
        }
        return $arabic_date;
    }
    /*
    Encription
     */
    public static function tokenencrypt($data)
    {
        $number=self::encmethod(self::$encryptMethod);
        $key = hash($number[0], self::$secretKey);
        $iv = substr(hash($number[0], self::$secretIv), 0, 16);
        $result = openssl_encrypt($data, self::$encryptMethod, $key, 0, $iv);
        return $result = base64_encode($result);
    }
    public static function tokendecrypt($data)
    {
        $number=self::encmethod(self::$encryptMethod);
        $key = hash($number[0], self::$secretKey);
        $iv = substr(hash($number[0], self::$secretIv), 0, 16);
        $result = openssl_decrypt(base64_decode($data), self::$encryptMethod, $key, 0, $iv);
        return $result;
    }
    private static function encmethod($text){
        $list=openssl_get_cipher_methods();
        $shalist=hash_algos();
        if(\Str::contains($text,'256')){$number='sha256';}
        elseif(\Str::contains($text,'128')){$number='sha128';}
        elseif(\Str::contains($text,'192')){$number='sha192';}
        if(!isset($number)){
            $number='sha256';
        }
        return [$number];
    }
    public static function LstTableID($table){
        if(\Illuminate\Support\Facades\Schema::hasTable($table))return \DB::table($table)->max('id')+1;
        return false;
    }
    public static function findage($dob)
        {
            //$dod=new \DateTime(
            return \Carbon\Carbon::createFromDate($dob->format('Y'), $dob->format('m'), $dob->format('d'))->diff(\Carbon\Carbon::now())->format('%y-%m-%d   ');
        }
}
