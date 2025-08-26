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
class homeAmerController extends Controller
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
        return view(Amerview('content.home'));
    }
}
