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

trait controllers{

    /**
     * findController
     *
     * @param  mixed $path
     * @param  mixed $name
     * @return void
     */
    public static function findController($path=null,$name=null){
        $allprojectfiles=self::array_flatten(self::allprojectfiles());
        $allprojectfiles=Arr::where($allprojectfiles,function($v,$k){
            return Str::contains($v,'ontroller');
        });
        foreach($allprojectfiles as $a=>$b){
            $filename=$b;
                $fp = fopen($filename, 'r');
                    $className = $buffer = '';
                        $i = 0;
                        while (!$className) {
                            if (feof($fp)) break;
                            $buffer .= fread($fp, 512);
                            $tokens = token_get_all($buffer);
                            if (strpos($buffer, '{') === false) continue;
                            for ($i;$i<count($tokens);$i++) {
                                if ($tokens[$i][0] === T_NAMESPACE) {
                                    $namespace=$tokens[$i+2][1];
                                }
                                if ($tokens[$i][0] === T_CLASS) {
                                    for ($j=$i+1;$j<count($tokens);$j++) {
                                        if ($tokens[$j] === '{') {
                                            if(!isset($tokens[$i+2][1])){
                                                continue;
                                            }
                                            $className = $tokens[$i+2][1];
                                            if(!isset($tokens[$i+4][1]))continue;
                                            if($tokens[$i+4][1] !== 'extends') continue;
                                            if(!in_array($tokens[$i+6][1],['AmerController','Controller'])) continue;

                                        }
                                    }
                                }
                                if(isset($namespace) && isset($className) && $className !== ''){
                                    $fullclassname[]=$namespace.'\\'.$className;
                                }
                            }
                        }
        }
        $fullclassname=collect($fullclassname);
        return($fullclassname->unique()->toArray());
    }

    /**
     * allprojectfiles
     *
     * @param  mixed $base
     * @return void
     */
    private static function allprojectfiles($base=null){
        if($base == null){$base=base_path();}
        $results = scandir($base);
        $blockedfiles=['.','..','.env','composer.json','composer.lock','README.md'];
        $files=[];
            foreach ($results as $result) {
                if (in_array($result,$blockedfiles) OR self::startsWith($result,'.') OR Str::contains($result,'.md') OR Str::contains($result,'.js') OR Str::contains($result,'.css') OR Str::contains($result,'.blade.php' OR Str::contains($result,'.tmp') OR Str::contains($result,'.xml'))) continue;
                $filename=$base.'/'.$result;
                if(\File::isDirectory($filename)){$files[]=self::allprojectfiles($filename);}
                if(\Str::endsWith($filename,'.php')){
                    $files[]=$filename;
                }
            }
            return $files;
    }
}
