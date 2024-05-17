<?php
use Illuminate\Support\Facades\Route;
use Amerhendy\Amer\App\Helpers\AmerHelper;
use Illuminate\Support\Facades\Auth;
////////////////////////////////////////
//file for Amer
// file for employers
// file for admin
// file for base
// file for public
// file for api amer
// file for api Amer
/////////////////////////////////////////
Route::group([
    'namespace' => config('Amer.amer.Controllers'),
], function () {
    Route::get('/QRCODE/{element}', 'qrcode@index')->middleware(['web'])->name('qrcode');
    Route::get('/QRCODE', 'qrcode@index')->middleware(['web'])->name('qrcodereader');
});
Route::group(
    [
        'prefix'=>config('Amer.amer.route_prefix','amer'),
        'namespace'  =>'Amerhendy\Amer\App\Http\Controllers',
        'middleware' =>array_merge((array) config('Amer.Security.web_middleware'),(array) config('Amer.Security.auth.middleware_key')),
        'name'=>config('Amer.amer.routeName_prefix','amer'),
    ],
    function(){
        Route::Amer('Governorates','GovernoratesAmerController');
        Route::Amer('Cities','CitiesAmerController');
});
Route::get('api/translator/{index}',function($index){
    return trans($index);
});