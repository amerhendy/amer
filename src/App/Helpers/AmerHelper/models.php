<?php
namespace Amerhendy\Amer\App\Helpers\AmerHelper;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use PDO;

trait models{
public static $modelPath;

    /**
     * modelexists
     *
     * @param  mixed $model
     * @return void
     */
    public static function modelexists($model){
        return (class_exists($model));
    }

    /**
     * getModels
     *
     * @param  mixed $modelPath
     * @return void
     */
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

    /**
     * getModels2
     *
     * @param  mixed $modelPath
     * @return void
     */
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

    /**
     * loadmodels
     *
     * @param  mixed $modelName
     * @return void
     */
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
}
