<?php
namespace Amerhendy\Amer\App\Helpers\AmerHelper;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait encription{
    public static $encryptMethod,$secretKey,$secretIv;
    public static function setEncryptData($encryptMethod=null,$secretKey=null,$secretIv=null){
        if(is_null($secretKey)){$secretKey=config('Amer.amer.SecretKey','Amer');}
        if(is_null($secretIv)){$secretIv=config('app.url',\URL::to('/'));}
        if(is_null($encryptMethod)){$encryptMethod=config('app.cipher','AES-256-CBC');}
        self::$secretKey=$secretKey;self::$secretIv=$secretIv;self::$encryptMethod=$encryptMethod;
    }
    public static function tokenencrypt($data)
    {
        self::setEncryptData(self::$encryptMethod,self::$secretKey,self::$secretIv);
        $number=self::encmethod(self::$encryptMethod);
        $key = hash($number[0], self::$secretKey);
        $iv = substr(hash($number[0], self::$secretIv), 0, 16);
        $result = openssl_encrypt($data, self::$encryptMethod, $key, 0, $iv);
        return $result = base64_encode($result);
    }
    public static function tokendecrypt($data)
    {
        self::setEncryptData(self::$encryptMethod,self::$secretKey,self::$secretIv);
        $number=self::encmethod(self::$encryptMethod);
        $key = hash($number[0], self::$secretKey);
        $iv = substr(hash($number[0], self::$secretIv), 0, 16);
        $result = openssl_decrypt(base64_decode($data), self::$encryptMethod, $key, 0, $iv);
        return $result;
    }
    private static function encmethod($text){
        $list=openssl_get_cipher_methods();
        $shalist=hash_algos();
        if(\Str::contains($text,'256')){$number='sha256';}
        elseif(\Str::contains($text,'128')){$number='sha128';}
        elseif(\Str::contains($text,'192')){$number='sha192';}
        if(!isset($number)){
            $number='sha256';
        }
        return [$number];
    }
}
