<?php
namespace Amerhendy\Amer\App\Helpers\AmerHelper;
use Exception;
use Illuminate\Support\Str;

trait arabic{
    static $arabicNumbers=[
        'standard' => array("0","1","2","3","4","5","6","7","8","9"),
        'eastern_arabic_symbols' => array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩")
    ];

    static function ArabicNumbersText($text){
        return str_replace(self::$arabicNumbers['standard'] , self::$arabicNumbers['eastern_arabic_symbols'] , $text);
    }
    static function ArabicDate($year,$month,$day,$hour=null,$minute=null,$am=null) {
        $newdate=new \DateTime();
        $newdate->setDate($year, $month, $day);
        $months = trans('AMER::trojan.months');
        $en_month = $newdate->format('M');
        $ar_month=trans('AMER::trojan.months.'.$en_month);
        $find = array ("Sat", "Sun", "Mon", "Tue", "Wed" , "Thu", "Fri");
        $replace = trans('AMER::trojan.days');
        $ar_day_format = $newdate->format('D');
        $ar_day = str_replace($find, $replace, $ar_day_format);
        $standard = array("0","1","2","3","4","5","6","7","8","9");
        $eastern_arabic_symbols = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
        $current_date = $ar_day.' '.trans("AMER::trojan.moafeq").' '.$newdate->format('d').' '.$ar_month.' '.$newdate->format('Y');
        $arabic_date=self::ArabicNumbersText($current_date);
        if(isset($hour)){
            $hour=23;
            if($hour > 12){
                $am='PM';
                $hour=$hour-12;
            }
            if(!isset($minute)){$minute =0;}if(!isset($am)){$am ='AM';}
            $am=trans('AMER::trojan.hour.'.$am);
            $time=\Str::replaceArray('?',[$hour,$minute,$am],trans("AMER::trojan.hourFullText"));
            $arabictime=self::ArabicNumbersText($time);
            return $arabic_date.' '.$arabictime;
        }
        return $arabic_date;
    }
}
