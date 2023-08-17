<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel\Traits;
use Amerhendy\Amer\App\Helpers\AmerHelper;
trait Clon
{
    public function Clon($id)
    {
        $model=$this->model;
        $as=$model::class;
        $av=new $as();
        $item=$av->findOrFail($id);
        $new = $item->replicate(['id'=>333]);
        $new->push();
        $item->relations = [];
        $allrelations=self::getAllRelations($item);
        foreach ($allrelations as $key => $value) {
            $item->load($key);
        }
        foreach ($item->relations as $relationName => $values){
            $new->{$relationName}()->sync($values);
        }
        return true;
    }
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
        $currentMethod = collect(explode('::', __METHOD__))->last();
        $filter = $types[$heritage];
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);  // The method must be public
        $methods = collect($methods)->filter(function ($method) use ($modelName, $traitMethodNames, $currentMethod,$model) {
        $methodName = $method->getName();
        if (($key = array_search('permissions', $traitMethodNames)) !== false) {
            unset($traitMethodNames[$key]);
        }
            if (!in_array($methodName, $traitMethodNames)   //The method must not originate in a trait
                && strpos($methodName, '__') !== 0  //It must not be a magic method
                && $method->class === $modelName    //It must be in the self scope and not inherited
                && !$method->isStatic() //It must be in the this scope and not static
                && $methodName != $currentMethod    //It must not be an override of this one
            ) {
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
}
