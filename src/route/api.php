<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('/',function(){
    return ['amer'];
});



////////////////////////////////////////////////////
    Route::GET('Regulations/data/{class}','api\allCollection@index')->name('api.alldata');
?>
