<?php
namespace Amerhendy\Amer\App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use AmerHelper;
use \Cviebrock\EloquentSluggable\Services\SlugService;
class StorageController extends Controller
{
    protected $currentClass;
    
    protected $settings = [];
    protected $currentOperation;
    protected $routeMethod;
    protected $id;
    public function __construct() {
    }
    public function __invoke(Request $request)
    {
        //
    }
    public function index()
    {
        if(auth::guard('Employers')->check())
        {
            
            $route = Route::currentRouteName();
            $user=auth::guard('Employers')->user();
            $userinfo='';
            $user['JobName']=Mosama_JobName::where('JobTitle_id',$user->Mosama_JobTitles)->where('Degree_id',$user->Mosama_Degrees)->where('Group_id',$user->Mosama_Groups)->get(['text','id']);
            return view('layout.Employers.dashboard',['user'=>$user,'Regulations'=>Regulations::all()]);
        }else{
            return view(Amerview('content.Employers.home'));
        }
        
    }
}