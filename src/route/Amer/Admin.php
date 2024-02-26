<?php
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix'     =>config('amer.route_prefix'),
        //'middleware' =>array_merge((array) config('amer.web_middleware'),(array) config('amerSecurity.auth.admin_auth.middleware_key')),
        'namespace'  =>'App\Http\Controllers'
    ],
    function(){
       // Route::Amer("Cities",CityAmerController::Class);
    });