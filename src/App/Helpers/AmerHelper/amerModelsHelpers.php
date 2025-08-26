<?php
namespace Amerhendy\Amer\App\Helpers\AmerHelper;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;
trait amerModelsHelpers{
    /**
     * createShortUrl
     *
     * @param  mixed $url
     * @param  mixed $time
     * * @param  mixed $unit ['seconds', 'minutes', 'hours', 'days','week','month','year'];
     * @return void
     */
    public static function createShortUrl($url,$time=60,$unit='minutes'){

        // التحويل إلى عدد الدقائق حسب الوحدة
        $timeUnits = [
            'minute' => 1,
            'hour'   => 60,
            'day'    => 1440,        // 60 * 24
            'week'   => 10080,       // 60 * 24 * 7
            'month'  => 43200,       // تقريبي: 60 * 24 * 30
            'year'   => 525600,      // 60 * 24 * 365
        ];
        $unit = strtolower($unit);
        if (!isset($timeUnits[$unit])) {
            throw new \InvalidArgumentException("الوحدة الزمنية غير مدعومة: $unit");
        }
        $minutesToAdd = $time * $timeUnits[$unit];
        $time = Carbon::now()->addMinutes($minutesToAdd);
        //check if link exists
        if(\Str::contains($url,"?expires=")){
            //search for
            $searchfor = (string) Str::of($url)->afterLast('/');
            $searchfor = (string) Str::of($searchfor)->before('?');
        }else{
            $searchfor = (string) Str::of($url)->afterLast('/');
        }
        $find=\Amerhendy\Amer\App\Models\ShortUrls::where('original','LIKE',"%".$searchfor."%")->first();
        $create = !$find;
        if($create){
            $shorten=substr(md5((string) \Str::uuid()),0,6);
            $new=new \Amerhendy\Amer\App\Models\ShortUrls();
            $new->original=$url;
            $new->shorten=$shorten;
            $new->time=$minutesToAdd;
            $new->expires_at=$time;
            $new->created_at=now();
            $new->save();
        }else{
            $shorten=$find->shorten;
            $find->forceFill([
            'original'    => $find->original,
            'shorten'     => $find->shorten,
            'time'        => $minutesToAdd,
            'expires_at'  => $time,
            'created_at'  => now(),
            ])->save();
        }
        return route('shortUrlreader', ['index' => $shorten]);
        
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
