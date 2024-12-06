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

trait arraytrait{
    /**
     * array_flatten
     *
     * @param  mixed $array
     * @return void
     */
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
    /**
     * arrayFlattenWKey
     *
     * @param  mixed $array
     * @param  mixed $prefix
     * @return void
     */
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
    /**
     * arrayFlatten
     *
     * @param  mixed $array
     * @return void
     */
    static function arrayFlatten($array){
        $result = [];
        foreach ($array as $key => $value) {
            if(is_array($value)) {
                $result[]=self::arrayFlattenWKey($value);
            }
        }
        return $result;
    }
    static function getkeyByValue($array,$value){
        $ret;
        foreach ($array as $k => $v) {
            if($v === $value){$ret=$k;}
        }
        return $ret;
    }
}
