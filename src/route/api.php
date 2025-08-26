<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('/',function(){
    return ['amer'];
});



////////////////////////////////////////////////////
    Route::GET('Regulations/data/{class}','\Amerhendy\Amer\App\Http\Controllers\api\allcollection@index')->name('api.alldata');
    Route::get('/generate-uuid/{count}', function ($count){
        $uuid=[];
        for($i=1;$i<=$count;$i++){
            $uuid[] = Str::uuid(); // توليد UUID
        }

        return response()->json(['uuid' => $uuid]);
    });
    Route::get('/ViewMenu', '\amerhendy\Amer\App\Http\Controllers\api\allcollection@getMenuTree');



    Route::group(
    [
        'namespace' => "\\".config('Amer.Amer.Controllers'),
        'middleware' =>['auth:Amer-api'],
        'prefix'=>config('Amer.Amer.route_prefix','amer'),
    ],function(){
        Route::Amer('Cities','CitiesAmerController');
    });
    Route::group(
    [
        'namespace' => "\\".config('Amer.Amer.Controllers'),
        'middleware' =>['auth:Amer-api'],
        'prefix'=>config('Amer.Amer.route_prefix','amer')."/Storage",
    ],function(){
            Route::get('list',          'StorageController@list');
            Route::post('upload',       'StorageController@upload');
            Route::post('create-folder','StorageController@createFolder');
            Route::post('delete',       'StorageController@delete');
            Route::post('archive',      'StorageController@archive');
            Route::post('unarchive',    'StorageController@unarchive');
            Route::get('history',       'StorageController@history');
            Route::get('link/{file}',   'StorageController@generateLink');
    });
?>
