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

trait bladetrait{
    public static $loaded=[];
    private static $string,$cleanstring,$htm,$clean,$fullTextHtml,$cleandata,$shortText;
    public static function isHtml($string)
    {
        return preg_match("/<[^<]+>/",$string,$m) != 0;
    }
    public static function isHasTags($string)
    {
        if($string == strip_tags($string)) {return false;}
        return true;
    }
    public static function setfullTextHtml($string){
            return '<div id="fullHtml" style="display:none">'.$string.'
                            <br><span role="link" class="badge bg-primary" style="cursor:pointer;" onclick="readmore(this,\'shortdata\')">(اقرأ أقل)</span>
                            <span role="link" class="badge bg-primary" style="cursor:pointer;" onclick="readmore(this,\'cleandata\')">(اقرأ بدون تنسيق)</span>
                        </div>';
    }
    public static function setCleanData($string){
        return '<div id="cleandata" style="display:none">'.$string.'
        <br><span role="link" class="badge bg-primary" style="cursor:pointer;" onclick="readmore(this,\'shortdata\')">(اقرأ أقل)</span>
        <span role="link" class="badge bg-info" style="cursor:pointer;" onclick="readmore(this,\'fullHtml\')">(عرض كامل)</span>
        </div>';
    }
    public static function setShortText($string){

        $shortText= '<div id="shortdata">';
        if(Str::of($string)->length()<40){
            $shortText.=$string;
        }else{
            $shortText.=\Str::limit($string, 50, '...');
        }

        if(Str::of($string)->length()<40){
            $shortText.='<span role="link" class="badge bg-primary" style="cursor:pointer;" onclick="readmore(this,\'cleandata\')"><i class="fa-solid fa-broom"></i></span>';
            $shortText.='<span role="link" class="badge bg-info" style="cursor:pointer;" onclick="readmore(this,\'fullHtml\')"><i class="fa fa-css3" aria-hidden="true"></i></span>';
        }else{
            $shortText.='<span role="link" class="badge bg-info" style="cursor:pointer;" onclick="readmore(this,\'fullHtml\')"><i class="fa fa-css3" aria-hidden="true"></i></span>';
        }
        $shortText.= '</div>';
        return $shortText;
    }
    /**
     * createhtmllimitstring
     *read full html text
     *read full clean text
     *read short text
     * @param  mixed $string
     * @return void
     */
    public static function createhtmllimitstring($string){
        if(gettype($string) !== 'string'){return $string;}
        if(Str::of(trim($string))->length()<40){return $string;}
        self::$string=$string;
        self::$htm=self::isHtml(self::$string);
        self::$clean=self::isHasTags($string);

        self::$fullTextHtml=self::setfullTextHtml(self::$string);
        self::$cleanstring=self::decodeHTMLEntities(self::$string);
        self::$cleandata=self::setCleanData(self::$cleanstring);
        self::$shortText=self::setShortText(self::$cleanstring);
        return self::$shortText.self::$cleandata.self::$fullTextHtml;
    }
    public static function decodeHTMLEntities($string){
        if(gettype($string) == 'string'){
            $string=strip_tags($string);
            $string=trim(preg_replace('/\s\s+/', ' ', $string));
            $string=Str::squish($string);
        }
        return $string;

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

    public static function delete_all_between($beginning, $end, $string) {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
          return $string;
        }

        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return self::delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
    }

    public static function getDataFromJSON($column){
        $keys=self::getDataFromJSONGetKeys($column);
        $vls=[];
        foreach ($column['value'] as $k => $v) {
            if(!is_array($v)){
                if(in_array($k,$keys)){
                    $vls[$k]=[$v];
                }
            }else{
                foreach ($keys as $l => $m) {
                    if(isset($v[$m])){
                        $vls[$m][]=$v[$m];
                    }
                }
            }
        }
        $dp=self::getDataFromJSONByKeys($column['get'],$keys,$vls);
        if(is_null($dp)){return $vls;}
        $dp=self::getDataFromJSONKeyTranslate($dp,$column);
        //if($column['name'] == 'startend'){dd($dp);}
        $dp=self::getDataFromJSONPretefi($dp);
        return $dp;

    }
    public static function getDataFromJSONGetKeys($column){
        $keys=[];
        foreach ($column['get'] as $k => $v) {
            if(!is_array($v)){
                $keys[]=$v;
            }else{
                $keys[]=$k;
            }
        }
        return $keys;
    }
    public static function getDataFromJSONByKeys($get,$keys,$vls){
        $dp=[];
        foreach ($keys as $key) {
            $do=$vls[$key];
            foreach ($do as $dos) {
                if(isset($get[$key]['data'])){
                    if(array_key_exists($dos,$get[$key]['data'])){
                        $dp[$key][]=$get[$key]['data'][$dos];
                    }
                }else{
                    $dp[$key][]=$dos;
                }
            }
        }
        return $dp;
    }
    public static function getDataFromJSONKeyTranslate($get,$column){
        $keys=[];
        foreach ($column['get'] as $k => $v) {
            if(is_array($v)){
                if(isset($v['KeyTranslate'])){
                    $keys[$k]=$v['KeyTranslate'];
                }
            }
        }
        if(is_null($keys)){return $get;}
        foreach ($get as $key => $value) {
            if(array_key_exists($key,$keys)){
                $get[$keys[$key]]=$value;
                unset($get[$key]);
            }
        }
        return $get;
    }
    public static function getDataFromJSONPretefi($get){
        if(count($get) == 1){
            foreach ($get as $key => $value) {
                //if(array_key_exists('startDate',$get)){dd($value,$key);}
                if($key == ''){
                    $get=implode(' - ',$value);
                }else{
                    $get[$key]=implode(' - ',$value);
                }
            }
            return $get;
        }else{
                foreach ($get as $key => $value) {
                    if(is_array($value) && count($value) == 1){
                        $get[$key]=implode(' - ',$value);
                    }
                }
                return $get;
        }
    }
}
