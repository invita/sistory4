<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {

    $entity = \App\Models\Entity::find(104);
    $entity->dataToObject();

    /*
    $process = new Process(realpath(__DIR__."/../vendor/goetas-webservices/xsd2php/bin/xsd2php")
        ." convert " .realpath(__DIR__."/../xsd2php.yml")." ".realpath(__DIR__."/../resources/assets/xsd/mets.xsd"));
    $process->run();
    */

    /*
    $_SERVER['argv'] = [
        realpath(__DIR__."/../vendor/goetas-webservices/xsd2php/bin/xsd2php"),
        "convert",
        realpath(__DIR__."/../xsd2php.yml"),
        realpath(__DIR__."/../resources/assets/xsd/mets.xsd")
    ];
    */

   // include realpath(__DIR__."/../vendor/goetas-webservices/xsd2php/bin/xsd2php");
    //passthru("vendor/goetas-webservices/xsd2php/bin/xsd2php convert xsd2php.yml resources/assets/xsd/mets.xsd");
});

Route::group(['prefix' => 'admin'], function () {

    Route::get('login', "Admin\\LoginController@index")->name("admin.login.index#get");
    Route::post('login', "Admin\\LoginController@index")->name("admin.login.index#post");
    Route::get('logout', "Admin\\LogoutController@index")->name("admin.logout.index#get");

    Route::group(['middleware' => 'auth'], function(){
        Route::get('/', "Admin\\IndexController@index")->name("admin.index.index#get");

        Route::group(['prefix' => 'api'], function () {
            Route::post('entity-list', "Admin\\ApiController@entityList")->name("admin.api.entity-list#post");
        });

        Route::group(['prefix' => 'upload'], function () {
            Route::post('entity', "Admin\\UploadController@entity")->name("admin.upload.entity#post");
        });
    });

});