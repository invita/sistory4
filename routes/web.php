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

Route::get('/storage/preview', "StorageController@preview")->name("storage.preview#get");

Route::group(['prefix' => 'admin'], function () {

    Route::get('login', "Admin\\LoginController@index")->name("admin.login.index#get");
    Route::post('login', "Admin\\LoginController@index")->name("admin.login.index#post");
    Route::get('logout', "Admin\\LogoutController@index")->name("admin.logout.index#get");

    Route::group(['middleware' => 'auth'], function(){
        Route::get('/', "Admin\\IndexController@index")->name("admin.index.index#get");

        // API routes
        Route::group(['prefix' => 'api'], function () {

            Route::post('initial-data', "Admin\\Api\\Initial@initialData")->name("admin.api.initial-data#post");

            Route::post('entity-list', "Admin\\Api\\Entities@entityList")->name("admin.api.entity-list#post");
            Route::post('reserve-entity-id', "Admin\\Api\\Entities@reserveEntityId")->name("admin.api.reserve-entity-id#post");
            Route::post('save-entity', "Admin\\Api\\Entities@saveEntity")->name("admin.api.save-entity#post");
            Route::post('delete-entity', "Admin\\Api\\Entities@deleteEntity")->name("admin.api.delete-entity#post");
            Route::post('entity-hierarchy', "Admin\\Api\\Entities@entityHierarchy")->name("admin.api.entity-hierarchy#post");

            Route::post('file-list', "Admin\\Api\\Files@fileList")->name("admin.api.file-list#post");
            Route::post('save-file', "Admin\\Api\\Files@saveFile")->name("admin.api.save-file#post");
            Route::post('delete-file', "Admin\\Api\\Files@deleteFile")->name("admin.api.delete-file#post");

            Route::post('user-list', "Admin\\Api\\Users@userList")->name("admin.api.entity-list#post");
            Route::post('save-user', "Admin\\Api\\Users@saveUser")->name("admin.api.save-user#post");
            Route::post('delete-user', "Admin\\Api\\Users@deleteUser")->name("admin.api.delete-user#post");

            Route::post('dev-tools', "Admin\\Api\\Dev@devTools")->name("admin.api.dev-tools#post");
        });

        Route::group(['prefix' => 'upload'], function () {
            Route::post('entity', "Admin\\UploadController@entity")->name("admin.upload.entity#post");
            Route::post('show-content', "Admin\\UploadController@showContent")->name("admin.upload.show-content#post");
            Route::post('import', "Admin\\UploadController@import")->name("admin.upload.import#post");
            Route::post('upload-file', "Admin\\UploadController@uploadFile")->name("admin.upload.upload-file#post");
        });

        Route::group(['prefix' => 'download'], function () {
            Route::post('entity', "Admin\\DownloadController@entity")->name("admin.download.entity#post");
            Route::post('export', "Admin\\DownloadController@export")->name("admin.download.export#post");
            //Route::get('export', "Admin\\DownloadController@export")->name("admin.download.export#get");
        });
    });
});
