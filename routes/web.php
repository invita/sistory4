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

//Route::get('/', function () {
//    return view('index');
//});


Route::get('/lang', "IndexController@lang")->name("index.lang#get");
Route::get('/lang/{lang}', "IndexController@lang")->name("index.lang#get");

Route::get('/', "IndexController@index")->name("index.index#get");
Route::get('/search', "SearchController@index")->name("search.index#get");
Route::get('/advanced-search', "SearchController@advanced")->name("search.advanced#get");
Route::get('/'.si4config('handlePrefix').'/{hdl}', "DetailsController@index")->name("details.index#get");
//Route::get('/details/{hdl}', "DetailsController@index")->name("details.index#get");

Route::get('/ajax/{name}', "AjaxController@index")->name("ajax.index#get");

Route::get('/test', "TestController@index")->name("test.index#get");
Route::get('/test/{id}', "TestController@index")->name("test.index#get");

Route::get('/oai', "OaiController@index")->name("oai.index#get");


// *** Storage ***
Route::get('/storage/preview', "StorageController@preview")->name("storage.preview#get");
Route::get('/storage/mets', "StorageController@mets")->name("storage.mets#get");
Route::get('/xsd/{name}', "XsdController@index")->name("xsd.index#get");

Route::get('/cdn/{hdl}/{file}', "CdnController@index")->name("cdn.index#get");


// *** Admin ***
Route::group(['prefix' => 'admin'], function () {

    Route::get('login', "Admin\\LoginController@index")->name("admin.login.index#get");
    Route::post('login', "Admin\\LoginController@index")->name("admin.login.index#post");
    Route::get('logout', "Admin\\LogoutController@index")->name("admin.logout.index#get");

    Route::group(['middleware' => 'auth'], function(){
        Route::get('/', "Admin\\IndexController@index")->name("admin.index.index#get");

        // API routes
        Route::group(['prefix' => 'api'], function () {

            // Initial data
            Route::post('initial-data', "Admin\\Api\\Initial@initialData")->name("admin.api.initial-data#post");

            // Dashboard
            Route::post('dashboard-files', "Admin\\Api\\Dashboard@dashboardFiles")->name("admin.api.dashboard-files#post");

            // Translations
            Route::post('translations-list', "Admin\\Api\\Translations@translationsList")->name("admin.api.translations-list#post");
            Route::post('save-translation', "Admin\\Api\\Translations@saveTranslation")->name("admin.api.save-translation#post");
            Route::post('delete-translation', "Admin\\Api\\Translations@deleteTranslation")->name("admin.api.delete-translation#post");
            Route::post('generate-translation-files', "Admin\\Api\\Translations@generateTranslationFiles")->name("admin.api.generate-translation-files#post");

            // Entities
            Route::post('entity-list', "Admin\\Api\\Entities@entityList")->name("admin.api.entity-list#post");
            Route::post('entity-list-db', "Admin\\Api\\Entities@entityListDb")->name("admin.api.entity-list-db#post");
            Route::post('reserve-entity-id', "Admin\\Api\\Entities@reserveEntityId")->name("admin.api.reserve-entity-id#post");
            Route::post('save-entity', "Admin\\Api\\Entities@saveEntity")->name("admin.api.save-entity#post");
            Route::post('delete-entity', "Admin\\Api\\Entities@deleteEntity")->name("admin.api.delete-entity#post");
            Route::post('entity-hierarchy', "Admin\\Api\\Entities@entityHierarchy")->name("admin.api.entity-hierarchy#post");
            Route::post('force-reindex-entity', "Admin\\Api\\Entities@forceReindexEntity")->name("admin.api.force-reindex-entity#post");
            Route::post('force-regen-thumb', "Admin\\Api\\Entities@forceRegenThumb")->name("admin.api.force-regen-thumb#post");

            Route::post('file-list', "Admin\\Api\\Files@fileList")->name("admin.api.file-list#post");
            Route::post('save-file', "Admin\\Api\\Files@saveFile")->name("admin.api.save-file#post");
            Route::post('delete-file', "Admin\\Api\\Files@deleteFile")->name("admin.api.delete-file#post");

            // Users
            Route::post('user-list', "Admin\\Api\\Users@userList")->name("admin.api.entity-list#post");
            Route::post('save-user', "Admin\\Api\\Users@saveUser")->name("admin.api.save-user#post");
            Route::post('delete-user', "Admin\\Api\\Users@deleteUser")->name("admin.api.delete-user#post");

            // Metadata Field definitions
            Route::post('field-definitions', "Admin\\Api\\MdFieldDefinitions@fieldDefinitions")->name("admin.api.field-definitions#post");
            Route::post('field-definitions-list', "Admin\\Api\\MdFieldDefinitions@fieldDefinitionsList")->name("admin.api.field-definitions-list#post");
            Route::post('save-field-definition', "Admin\\Api\\MdFieldDefinitions@saveFieldDefinition")->name("admin.api.save-field-definition#post");
            Route::post('delete-field-definition', "Admin\\Api\\MdFieldDefinitions@deleteFieldDefinition")->name("admin.api.delete-field-definition#post");

            // Metadata Mapping
            Route::post('mapping-group-list', "Admin\\Api\\MdMapping@mappingGroupList")->name("admin.api.mapping-group-list#post");
            Route::post('save-mapping-group', "Admin\\Api\\MdMapping@saveMappingGroup")->name("admin.api.save-mapping-group#post");
            Route::post('delete-mapping-group', "Admin\\Api\\MdMapping@deleteMappingGroup")->name("admin.api.delete-mapping-group#post");

            Route::post('mapping-group-fields-list', "Admin\\Api\\MdMapping@mappingGroupFieldsList")->name("admin.api.mapping-group-fields-list#post");
            Route::post('save-mapping-group-field', "Admin\\Api\\MdMapping@saveMappingGroupField")->name("admin.api.save-mapping-group-field#post");
            Route::post('delete-mapping-group-field', "Admin\\Api\\MdMapping@deleteMappingGroupField")->name("admin.api.delete-mapping-group-field#post");

            // Metadata Behaviour
            Route::post('behaviour-list', "Admin\\Api\\MdBehaviour@behaviourList")->name("admin.api.behaviour-list#post");
            Route::post('save-behaviour', "Admin\\Api\\MdBehaviour@saveBehaviour")->name("admin.api.save-behaviour#post");
            Route::post('delete-behaviour', "Admin\\Api\\MdBehaviour@deleteBehaviour")->name("admin.api.delete-behaviour#post");

            // Metadata Behaviour Fields
            Route::post('behaviour-field-list', "Admin\\Api\\MdBehaviour@behaviourFieldList")->name("admin.api.behaviour-field-list#post");
            Route::post('save-behaviour-field', "Admin\\Api\\MdBehaviour@saveBehaviourField")->name("admin.api.save-behaviour-field#post");
            Route::post('delete-behaviour-field', "Admin\\Api\\MdBehaviour@deleteBehaviourField")->name("admin.api.delete-behaviour-field#post");

            // Oai Groups
            Route::post('oai-group-list', "Admin\\Api\\Oai@oaiGroupList")->name("admin.api.oai-group-list#post");
            Route::post('save-oai-group', "Admin\\Api\\Oai@saveOaiGroup")->name("admin.api.save-oai-group#post");
            Route::post('delete-oai-group', "Admin\\Api\\Oai@deleteOaiGroup")->name("admin.api.delete-oai-group#post");

            // Oai Fields
            Route::post('oai-group-fields-list', "Admin\\Api\\Oai@oaiGroupFieldsList")->name("admin.api.oai-group-fields-list#post");
            Route::post('save-oai-group-field', "Admin\\Api\\Oai@saveOaiGroupField")->name("admin.api.save-oai-group-field#post");
            Route::post('delete-oai-group-field', "Admin\\Api\\Oai@deleteOaiGroupField")->name("admin.api.delete-oai-group-field#post");

            // Metadata Mapping tests
            Route::post('xml-to-si4-test', "Admin\\Api\\MdTests@xmlToSi4Test")->name("admin.api.xml-to-si4-test#post");
            Route::post('xml-x-path-test', "Admin\\Api\\MdTests@xmlXPathTest")->name("admin.api.xml-x-path-testt#post");

            Route::post('dev-tools', "Admin\\Api\\Dev@devTools")->name("admin.api.dev-tools#post");
        });

        Route::group(['prefix' => 'upload'], function () {
            Route::post('entity', "Admin\\UploadController@entity")->name("admin.upload.entity#post");
            Route::post('show-content', "Admin\\UploadController@showContent")->name("admin.upload.show-content#post");
            Route::post('import-check', "Admin\\UploadController@importCheck")->name("admin.upload.importCheck#post");
            Route::post('import', "Admin\\UploadController@import")->name("admin.upload.import#post");
            Route::post('upload-file', "Admin\\UploadController@uploadFile")->name("admin.upload.upload-file#post");
        });

        Route::group(['prefix' => 'download'], function () {
            Route::post('entity', "Admin\\DownloadController@entity")->name("admin.download.entity#post");
            Route::post('exportMets', "Admin\\DownloadController@exportMets")->name("admin.download.exportMets#post");
            Route::post('exportCsv', "Admin\\DownloadController@exportCsv")->name("admin.download.exportCsv#post");
            //Route::get('export', "Admin\\DownloadController@export")->name("admin.download.export#get");
        });
    });
});
