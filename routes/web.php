<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Routing\Router;
use Exceedone\Exment\Model\CustomTable;
use Exceedone\Exment\Model\File;

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

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => 'Exceedone\Exment\Controllers',
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'DashboardController@home');
    $router->get('dashboardbox/html/{suuid}', 'DashboardBoxController@getHtml');
    $router->delete('dashboardbox/delete/{suuid}', 'DashboardBoxController@delete');
    $router->resource('dashboard', 'DashboardController');
    $router->get("dashboardbox/table_views", 'DashboardBoxController@tableViews');
    $router->resource('dashboardbox', 'DashboardBoxController');
    $router->resource('auth/menu', 'MenuController', ['except' => ['create']]);
    $router->get('auth/setting', 'AuthController@getSetting');
    $router->put('auth/setting', 'AuthController@putSetting');

    $router->get('system', 'SystemController@index');
    $router->post('system', 'SystemController@post');
    $router->get('system/update', 'SystemController@updatePackage');
    
    $router->get('template', 'TemplateController@index');
    $router->post('template/import', 'TemplateController@import');
    $router->post('template/export', 'TemplateController@export');

    $router->resource('plugin', 'PluginController');
    $router->resource('authority', 'AuthorityController');
    $router->resource('table', 'CustomTableController');
    $router->resource('loginuser', 'LoginUserController');
    $router->resource('mail', 'MailTemplateController');
    $router->get('notify/targetcolumn', 'NotifyController@targetcolumn');
    $router->get('notify/notify_action_target', 'NotifyController@notify_action_target');
    $router->resource('notify', 'NotifyController');

    if(Schema::hasTable(CustomTable::getTableName())){
        foreach (CustomTable::all()->pluck('table_name') as $value)
        {
            $router->post("data/{$value}/import", 'CustomValueController@import');
            $router->resource("data/{$value}", 'CustomValueController');
            //$router->get("data/{$value}/{id}/document", 'CustomValueController@getDocumentForm');
            //$router->post("data/{$value}/{id}/document", 'CustomValueController@postDocumentForm');
            $router->resource("column/{$value}", 'CustomColumnController');
            $router->resource("form/{$value}", 'CustomFormController');
            $router->get("view/{$value}/filter-condition", 'CustomViewController@getFilterCondition');
            $router->resource("view/{$value}", 'CustomViewController');
            $router->resource("relation/{$value}", 'CustomRelationController');
            $router->get("navisearch/data/{$value}", 'NaviSearchController@getNaviData');
            $router->post("navisearch/result/{$value}", 'NaviSearchController@getNaviResult');
            $router->get("api/{$value}/query", 'ApiController@query');
            $router->post("api/{$value}/{id}", 'ApiController@find');
        }
    }

    $router->get('search', 'SearchController@index');
    $router->post('search/list', 'SearchController@getList');
    $router->post('search/header', 'SearchController@header');
    $router->post('search/relation', 'SearchController@getRelationList');

    $router->get("api/target_table/columns/{id}", 'ApiController@targetBelongsColumns');
    $router->get('api/table/{id}', 'ApiController@table');
    $router->get('api/menu/menutype', 'MenuController@menutype');
    $router->post('api/menu/menutargetvalue', 'MenuController@menutargetvalue');

    // TODO:hard coding
    $router->get("data/estimate/{id}/doc", 'CustomValueController@getDocumentForm');
    $router->post("data/estimate/{id}/doc", 'CustomValueController@postDocumentForm');
    $router->get("data/invoice/{id}/doc", 'CustomValueController@getDocumentForm');
    $router->post("data/invoice/{id}/doc", 'CustomValueController@postDocumentForm');


    $router->get('files/{uuid}', function($uuid){
        return File::download($uuid);
    });
});

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => 'Exceedone\Exment\Controllers',
    'middleware'    => ['web', 'admin_anonymous'],
], function (Router $router) {
    $router->get('initialize', 'InitializeController@index');
    $router->post('initialize', 'InitializeController@post');
    $router->get('auth/login', 'AuthController@getLoginExment');
    $router->get('auth/forget', 'ForgetPasswordController@showLinkRequestForm');
    $router->post('auth/forget', 'ForgetPasswordController@sendResetLinkEmail')->name('password.email');
    $router->get('auth/reset/{token}', 'ResetPasswordController@showResetForm');
    $router->post('auth/reset/{token}', 'ResetPasswordController@reset')->name('password.request');
});