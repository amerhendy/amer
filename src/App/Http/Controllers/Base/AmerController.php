<?php
namespace Amerhendy\Amer\App\Http\Controllers\Base;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class AmerController extends Controller
{
    use DispatchesJobs, ValidatesRequests;
    public $Amer;
    public $data = [];

    public function __construct()
    {
        if ($this->Amer) {
            return;
        }
        //dd(get_class_methods(app('Amer')));
        $this->middleware(function ($request, $next) {
            $this->Amer = app('Amer');
            $this->Amer->setRequest($request);
            $this->setupDefaults();
            $this->setup();
            $this->setupConfigurationForCurrentOperation();
            return $next($request);
        });
    }
    public function setup()
    {
    }
    public function setupRoutes($segment, $routeName, $controller)
    {
        preg_match_all('/(?<=^|;)setup([^;]+?)Routes(;|$)/', implode(';', get_class_methods($this)), $matches);
        if (count($matches[1])) {
            foreach ($matches[1] as $methodName) {
                $this->{'setup'.$methodName.'Routes'}($segment, $routeName, $controller);
            }
        }
    }
    protected function setupDefaults()
    {
        preg_match_all('/(?<=^|;)setup([^;]+?)Defaults(;|$)/', implode(';', get_class_methods($this)), $matches);
        if (count($matches[1])) {
            foreach ($matches[1] as $methodName) {
                $this->{'setup'.$methodName.'Defaults'}();
            }
        }
    }
    protected function setupConfigurationForCurrentOperation()
    {

        $operationName = $this->Amer->getCurrentOperation();
        if (! $operationName) {
            return;
        }
        $setupClassName = 'setup'.Str::studly($operationName).'Operation';
        $this->Amer->applyConfigurationFromSettings($operationName);
        if (method_exists($this, $setupClassName)) {
            $this->{$setupClassName}();
        }
    }
    public function handleRequest(Request $request){
        //check if incoming request contains JSON data
        if($request->isJson()){
            $data=$request->json()->all();
        }else{
            dd($request);
        }
        if($request->wantsJson()){
            return response()->json(['result'=>'success']);
        }else{
            return response()->view('element');
        }
    }
}
