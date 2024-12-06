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
    'namespace' => config('Amer.Amer.Controllers'),
], function () {
    Route::get('/QRCODE/{element}', 'qrcode@index')->middleware(['web'])->name('qrcode');
    Route::get('/QRCODE', 'qrcode@index')->middleware(['web'])->name('qrcodereader');
});
Route::group(
    [
        'prefix'=>config('Amer.Amer.route_prefix','amer'),
        'namespace'  =>'Amerhendy\Amer\App\Http\Controllers',
        'middleware' =>array_merge((array) config('Amer.Security.web_middleware'),(array) config('Amer.Security.auth.middleware_key')),
        'name'=>config('Amer.Amer.routeName_prefix','amer'),
    ],
    function(){
        Route::Amer('Governorates','GovernoratesAmerController');
        Route::Amer('Cities','CitiesAmerController');
        Route::Amer('Menu','MenuAmerController');
});
Route::get('translator/{index}',function($index){
    return trans($index);
});
Route::get('name/{index}','Amerhendy\Amer\App\Http\Controllers\api\allcollection@namingconventions');
//Route::get('/','Amerhendy\Amer\App\Http\Controllers\homeAmerController@index')->name('index');
