<?php
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix'     =>config('Amer.Amer.route_prefix'),
        //'middleware' =>array_merge((array) config('Amer.Amer.web_middleware'),(array) config('Amer.Security.auth.admin_auth.middleware_key')),
        'namespace'  =>'App\Http\Controllers'
    ],
    function(){
       // Route::Amer("Cities",CityAmerController::Class);
    });
