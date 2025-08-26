<?php
namespace Amerhendy\Amer\App\Helpers\AmerHelper;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public static function responseForVue($data_arr,$draw=null,$classcount=null,$recordsFiltered=null){
        /*  0 => "model"
  1 => "route"
  2 => "routelist"
  3 => "entity_name"
  4 => "entity_name_plural"
  5 => "entry"
  6 => "query"
  7 => "totalQuery"
  */
  //dd($data_arr->data['Amer']);
        //dd(array_keys(get_object_vars($data_arr)));
        $response = array(
            "layout"=>$data_arr['layout'] ?? '',
            "draw" => intval($draw),
            "recordsTotal" => $classcount,
            "recordsFiltered" => $recordsFiltered,
           "data" => $data_arr
         );
         return response()->json($response,200);
    }
    
    public static function checkTokenGuard(Request $request)
    {
        $guards = array_keys(config('auth.guards'));
        //dd($guards);
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                //dd($guard);
                return true;
            }
        }
        return false;
    }
    public static function is_Json(Request $request){
        if($request->acceptsJson()){return true;}
        return false;
    }
    public static function returnLayoutData($return){
        $data=[];
        $data['layout']=$return['layout'];
        $return = \Arr::except($return, ['layout']);
        $data['settings']=$return['settings'];
        $return = \Arr::except($return, ['settings']);
        $data['routeList']=$return['routeList'];
        $return = \Arr::except($return, ['routeList']);
        $data['title']=$return['title'];
        $return = \Arr::except($return, ['title']);
        $data['currentOperation']=$return['currentOperation'];
        $return = \Arr::except($return, ['currentOperation']);
        $data['data']=$return;
        return response()->json($data,200);
    }
}
