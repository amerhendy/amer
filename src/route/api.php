<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('api',function(){
    return ['amer'];
});


    
////////////////////////////////////////////////////

Route::group([
    'namespace' => config('amer.Controllers'),
    'prefix' => config('amer.base.route_prefix','Amer/api'),
], function () {
    Route::GET('Regulations/data/{class}','api\allCollection@index')->name('api.alldata');
});
?>