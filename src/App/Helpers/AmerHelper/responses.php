<?php
namespace Amerhendy\Amer\App\Helpers\AmerHelper;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait responses{
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
}
