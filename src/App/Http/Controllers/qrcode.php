<?php
namespace Amerhendy\Amer\App\Http\Controllers;
use App\Http\Controllers\Controller;
use AmerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Amerhendy\Amer\App\Models\ShortUrls;
use Illuminate\Support\Carbon;
class qrcode extends Controller{
    public static function index($element=null,Request $request){
        $amerhelper=new \AmerHelper();
        if(is_null($element)){return view(mainview('main.qr'),['element'=>$element]);}
        $element = $request->query();
        $element=$amerhelper::decryptData(array_keys($element)[0]);
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
    public static function shortUrl($index) {
        $short = ShortUrls::where('shorten', $index)->first();
            if (!$short) {
        return response('الرابط غير موجود', 404);
        }
        if (Carbon::now()->greaterThan($short->expires_at)) {
            return response('انتهت صلاحية الرابط', 410); // 410 Gone
        }
        $url = $short->original;
        if (!\Str::startsWith($url, ['http://', 'https://'])) {
            $url = 'http://' . $url;
        }
        $short->increment('visits');
        return redirect()->to($url);
    }
    private function printstring(){}
}
?>