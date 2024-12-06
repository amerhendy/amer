<?php
namespace Amerhendy\Amer\App\Helpers\AmerHelper;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait amerModelsHelpers{
    /**
     * createShortUrl
     *
     * @param  mixed $url
     * @param  mixed $minutes
     * @return void
     */
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

    public static function LandLineCode(){
        $result=\DB::table('Cities')->select('LandLineCode')->groupBy('LandLineCode')->get();
        return $result;
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
    /**
     * retunFetchValue
     *  it help to return values from select2 fetch data
     * @param  mixed $text
     * @return void
     */
    public static function retunFetchValue($text){
        $request=Request();
        if ($request->has('form')) {
            $ob=(\Arr::where($request['form'],function($v,$k)use($text){
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
        }elseif ($request->has('dependencies')) {
            $ob=(\Arr::where($request['dependencies'],function($v,$k)use($text){
                return \Str::contains($v['input'],$text);
            }));
            $ret=[];
            foreach ($ob as $key => $value) {
                if(gettype($value['val']) == 'string'){$value['val']=[$value['val']];}
                $ret[$value['input']]=$value['val'];
            }
            return $ret;
        }
    }
    /**
     * get_loaded_providers
     *
     * @param  mixed $provider
     * @return void
     */
    public static function get_loaded_providers($provider=null){
        $prov=app()->getLoadedProviders();
        if($provider == null){return $prov;}
        if(array_key_exists($provider,$prov)){
            return true;
        }
        return false;
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
}
