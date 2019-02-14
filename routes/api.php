<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
  $api->get('/',            ['as' => 'api.home',   'uses' => 'App\Api\V1\Controllers\Controller@home']);
  $api->post('/UserLogin',  ['as' => 'user.login', 'uses' => 'App\Api\V1\Controllers\LoginController@doLogin']);


  $api->post('/buildingCreate',  ['as' => 'build.c', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingCreate']);
  $api->post('/buildingSearch',  ['as' => 'build.r', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingSearch']);
  $api->post('/buildingEdit',    ['as' => 'build.u', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingEdit']);
  $api->post('/buildingDelete',  ['as' => 'build.d', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingDelete']);

  $api->post('/seatCreate',  ['as' => 'build.c', 'uses' => 'App\Api\V1\Controllers\InfraController@seatCreate']);
  $api->post('/seatSearch',  ['as' => 'build.r', 'uses' => 'App\Api\V1\Controllers\InfraController@seatSearch']);
  $api->post('/seatEdit',    ['as' => 'build.u', 'uses' => 'App\Api\V1\Controllers\InfraController@seatEdit']);
  $api->post('/seatDelete',  ['as' => 'build.d', 'uses' => 'App\Api\V1\Controllers\InfraController@seatDelete']);


});
