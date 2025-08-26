<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Compilers\BladeCompiler;
use Amerhendy\Amer\App\Helpers\AssetManager;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\{Str,Stringable};
if (! function_exists('Amerurl')) {
    function Amerurl($path = null, $parameters = [], $secure = null)
    {
        $path = ! $path || (substr($path, 0, 1) == '/') ? $path : '/'.$path;
        return url(config('Amer.amer.routeName_prefix', 'amer').$path, $parameters, $secure);
    }
}
if (! function_exists('amer_middleware')) {
    function amer_middleware()
    {
        return config('Amer.Amer.middleware_key', 'Amer');
    }
}
if (! function_exists('Amer_guard_name')) {
    function Amer_guard_name($type=null)
    {
        $token=request()->bearerToken();
        $gu=checkTokenGuard(request(),'get');
        if($gu){
            return $gu;
        }
        //dd(\Str::singular(config('Amer.Security.auth.middleware_key')),config('auth.defaults.guard'));
        if($type == null){
            return config('Amer.Security.auth.middleware_key');
        }else{
            return config('Amer.Employers.auth.middleware_key', config('auth.defaults.guard'));
        }

    }
}
if (! function_exists('amer_auth')) {
    function amer_auth($type=null)
    {

        if($type == null){
            return \Auth::guard(Amer_guard_name());
        }else{
            return \Auth::guard(Amer_guard_name($type));
        }

    }
}
if (! function_exists('amer_user')) {
    function amer_user()
    {
        return amer_auth()->user();
    }
}
if (! function_exists('authentication_column')) {
    /**
     * Return the username column name.
     * The Laravel default (and Backpack default) is 'email'.
     *
     * @return string
     */
    function authentication_column()
    {
        return config('Amer.amer.authentication_column', 'email');
    }
}

if (! function_exists('email_column')) {
    /**
     * Return the email column name.
     * The Laravel default (and Backpack default) is 'email'.
     *
     * @return string
     */
    function email_column()
    {
        return config('Amer.amer.email_column', 'email');
    }
}
if (! Str::hasMacro('dotsToSquareBrackets')) {
    Str::macro('dotsToSquareBrackets', function ($string, $ignore = [], $keyFirst = true) {
        $stringParts = explode('.', $string);
            $result = '';

            foreach ($stringParts as $key => $part) {
                if (in_array($part, $ignore)) {
                    continue;
                }
                $result .= ($key === 0 && $keyFirst) ? $part : '['.$part.']';
            }

            return $result;
    });
}
if (! function_exists('Amerview')) {
    function Amerview($view)
    {
        $originalTheme = 'Amer::';
        $theme = config('Amer.amer.view_namespace');

        if (is_null($theme)) {
            $theme = $originalTheme;
        }

        $returnView = $theme.$view;
        if (! view()->exists($returnView)) {
            $returnView = $originalTheme.$view;
        }

        return $returnView;
    }
}
if (! function_exists('Baseview')) {
    function Baseview($view)
    {
        $originalTheme = 'Amer::';
        $theme = config('Amer.amer.view_namespace');
        if (is_null($theme)) {
            $theme = $originalTheme;
        }
        $theme.='Base.';
        $returnView = $theme.$view;
        if (! view()->exists($returnView)) {
            $returnView = $originalTheme.$view;
        }
        return $returnView;
    }
}
if (! function_exists('mainview')) {
    function mainview($view)
    {
        $originalTheme = 'Amer::';
        $theme = config('Amer.amer.view_namespace');
        if (is_null($theme)) {
            $theme = $originalTheme;
        }
        $theme.='Base.page.';
        $returnView = $theme.$view;
        if (! view()->exists($returnView)) {
            $returnView = $originalTheme.$view;
        }
        return $returnView;
    }
}
if (! function_exists('fieldview')) {
    function fieldview($view)
    {
        $originalTheme = 'Amer::';
        $theme = config('Amer.amer.view_namespace');
        if (is_null($theme)) {
            $theme = $originalTheme;
        }
        $theme.='Base.page.main.Forms.fields.';
        $returnView = $theme.$view;
        if (! view()->exists($returnView)) {
            $returnView = $originalTheme.$view;
        }
        return $returnView;
    }
}
if (! function_exists('listview')) {
    function listview($view)
    {
        $originalTheme = 'Amer::';
        $theme = config('Amer.amer.view_namespace');
        if (is_null($theme)) {
            $theme = $originalTheme;
        }
        $theme.='Base.page.main.List.';
        $returnView = $theme.$view;
        if (! view()->exists($returnView)) {
            $returnView = $originalTheme.$view;
        }
        return $returnView;
    }
}
if (! function_exists('Widgetview')) {
    function Widgetview($view)
    {
        $originalTheme = 'Amer::';
        $theme = config('Amer.amer.view_namespace');
        if (is_null($theme)) {
            $theme = $originalTheme;
        }
        $theme.='Base.page.main.Widgets.';
        $returnView = $theme.$view;
        if (! view()->exists($returnView)) {
            $returnView = $originalTheme.$view;
        }
        return $returnView;
    }
}
    $this->callAfterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
        $bladeCompiler->directive('loadStyleOnce', function ($parameter) {
            $AssetManager=new AssetManager();
            return "<?php AmerHelper::echoCss({$parameter}); ?>";
        });
        $bladeCompiler->directive('loadScriptOnce', function ($parameter) {
                return "<?php AmerHelper::echoJs({$parameter}); ?>";

        });
        $bladeCompiler->directive('loadOnce', function ($parameter) {
            // determine if it's a CSS or JS file
            $cleanParameter = Str::of($parameter)->trim("'")->trim('"')->trim('`');
            $filePath = Str::of($cleanParameter)->before('?')->before('#');

            // mey be useful to get the second parameter
            // if (Str::contains($parameter, ',')) {
            //     $secondParameter = Str::of($parameter)->after(',')->trim(' ');
            // }

            if (substr($filePath, -3) == '.js') {
                return "<?php AmerHelper::echoJs({$parameter}); ?>";
            }

            if (substr($filePath, -4) == '.css') {
                return "<?php AmerHelper::echoCss({$parameter}); ?>";
            }

            // it's a block start
            return "<?php if(! AmerHelper::isLoaded('".$cleanParameter."')) { AmerHelper::markAsLoaded('".$cleanParameter."');  ?>";
        });
        $bladeCompiler->directive('endLoadOnce', function () {
            return '<?php } ?>';
        });
    });
if( ! function_exists('cleanDir')){
    function cleanDir($dir){
        if(is_array($dir)){
            $dir=implode(DIRECTORY_SEPARATOR,$dir);
        }
        if(DIRECTORY_SEPARATOR == '/'){$err='\\';}else{$err='/';}
        $dir=\Str::finish($dir, DIRECTORY_SEPARATOR);
        $dir=\Str::replace($err, DIRECTORY_SEPARATOR, $dir);
        return realpath($dir);
    }
}
if( ! function_exists('getallfiles')){
    function getallfiles($path){
        $path=cleanDir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        $out=[];
        foreach($files as $a=>$b){
            if(is_dir($path.DIRECTORY_SEPARATOR.$b)){
                $out=array_merge($out,getallfiles($path.DIRECTORY_SEPARATOR.$b));
            }else{
                $ab=Str::finish($path,DIRECTORY_SEPARATOR);
                $out[]=$ab.$b;
            }
        }
        return $out;
    }
}
/*****************************/
/*
create Route::Amer
Instead of Rout::GET/any
*/
if (! Route::hasMacro('Amer')) {
    Route::macro('Amer', function ($name, $controller) {
        if(Str::contains($controller, '@')){
            $class=Str::before($controller,'@');
        }else{$class=$controller;}
        $routeName = '';
        if ($this->hasGroupStack()) {
            foreach ($this->getGroupStack() as $key => $groupStack) {
                if (isset($groupStack['name'])) {
                    if (is_array($groupStack['name'])) {
                        $routeName = implode('', $groupStack['name']);
                    } else {
                        $routeName = (string) \Str::of($groupStack['name'])->finish('.') ;
                    }
                }
            }
        }
        $routeName .= $name;
        if ($this->hasGroupStack()) {
            $groupStack = $this->getGroupStack();
            $groupNamespace = $groupStack && isset(end($groupStack)['namespace']) ? end($groupStack)['namespace'].'\\' : '';
        } else {
            $groupNamespace = '';
        }
        $namespacedController = $groupNamespace.$class;
        $controllerInstance = App::make($namespacedController);
        return $controllerInstance->setupRoutes($name, $routeName, $class);
    });
}
if (! function_exists('mb_ucfirst')) {
    function mb_ucfirst($string, $encoding = false)
    {
        $encoding = $encoding ? $encoding : mb_internal_encoding();

        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);

        return mb_strtoupper($firstChar, $encoding).$then;
    }
}
if (! function_exists('square_brackets_to_dots')) {
    function square_brackets_to_dots($string)
    {
        $string = str_replace(['[', ']'], ['.', ''], $string);

        return $string;
    }
}
if (! function_exists('old_empty_or_null')) {
    function old_empty_or_null($key, $empty_value = '')
    {
        $key = square_brackets_to_dots($key);
        $old_inputs = session()->getOldInput();
        // if the input name is present in the old inputs we need to return earlier and not in a coalescing chain
        // otherwise `null` aka empty will not pass the condition and the field value would be returned.
        if (\Arr::has($old_inputs, $key)) {
            return \Arr::get($old_inputs, $key) ?? $empty_value;
        }
        return null;
    }
}

if (! function_exists('is_multidimensional_array')) {
    function is_multidimensional_array(array $array)
    {
        foreach ($array as $item) {
            if (is_array($item)) {
                return true;
            }
        }

        return false;
    }
}
Blueprint::macro('uid', function () {
    $this->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
});
Blueprint::macro('dates', function () {
    $this->timestampsTz(precision: 0);
    $this->softDeletes('deleted_at', precision: 0);
});
?>
