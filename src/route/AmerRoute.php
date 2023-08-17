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
    'namespace' => config('amer.Controllers'),
], function () {
    Route::get('/QRCODE', 'qrcode@index')->middleware(['web'])->name('qrcode');
});
Route::group(
    [
        'prefix'=>config('amer.route_prefix','amer'),
        'namespace'  =>'Amerhendy\Employers\App\Http\Controllers',
        'middleware' =>array_merge((array) config('amer.web_middleware'),(array) config('amer.admin_auth.middleware_key')),
        'name'=>'admin.',
    ],
    function(){
        Route::GET('Mosama','MosamaAmerController')->name('admin.Mosama.index');
        Route::POST('Mosama/Mosama_print','MosamaAmerController')->name('admin.Mosama_print.index');
        Route::post('MosamaPrint','api\MosamaCollection@showprintjobname')->name('admin.showprintjobname');
});