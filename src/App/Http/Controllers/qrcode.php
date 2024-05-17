<?php
namespace Amerhendy\Amer\App\Http\Controllers;
use App\Http\Controllers\Controller;
use AmerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class qrcode extends Controller{
    public static function index($element=null,Request $request){
        $amerhelper=new \AmerHelper();
        if(is_null($element)){return view(mainview('main.qr'),['element'=>$element]);}
        $element=$amerhelper::tokendecrypt($element);
        if(\Str::isJson($element)){
            $element=json_decode($element,true);
            if(isset($element['actiontype'])){
                if($element['actiontype'] == 'apply'){
                    if(isset($element['requestType'])){
                        if($element['requestType']== 'review'){
                            $db=DB::table('Employment_ApplyLog')
                            ->where('id',$element['test'])
                            ->whereJsonContains('userData->NID','28807051203034')
                            ->first();
                            if($db){
                                $json=json_decode($db->userData,true);
                                foreach($json as $a=>$b){
                                    $request->merge([$a=>$b]);
                                }
                                $request->merge(['test'=>$element['test']]);
                                $cl=\Amerhendy\Employment\App\Http\Controllers\apply::class;
                                return $cl::review($request,'qrcode');
                            }
                            return view('errors.layout',['error_number'=>405,'error_message'=>'Not Review']);
                        }elseif($element['requestType'] == 'apply-review'){
                            //$db=DB::table('Employment_ApplyLog')->where('id',$element['test'])->whereJsonContains('userData->NID','28807051203034')->first();
                            $db=DB::table('Employment_ApplyLog')
                            ->where('id',$element['test'])
                            ->whereJsonContains('userData->NID','28807051203034')
                            ->first();
                            if($db){
                                $json=json_decode($db->userData,true);
                                foreach($json as $a=>$b){
                                    $request->merge([$a=>$b]);
                                }
                                $request->merge(['test'=>$element['test']]);
                                $cl=\Amerhendy\Employment\App\Http\Controllers\apply::class;
                                return $cl::review($request,'qrcode');
                            }
                            return view('errors.layout',['error_number'=>405,'error_message'=>'Not Applied']);
                            
                        }
                    }
                }
            }
            
        }else{
            if (filter_var($element, FILTER_VALIDATE_URL) || filter_var($element, FILTER_VALIDATE_EMAIL) || filter_var($element, FILTER_VALIDATE_IP)) {
                return \Redirect::to($element);
             }else{
                return $element;
             }
        }
    }
    private function printstring(){}
}
?>