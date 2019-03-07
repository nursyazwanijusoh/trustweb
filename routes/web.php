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

Auth::routes();

Route::get('/', 'HomeController@welcome')->name('welcome');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/pg', 'HomeController@playground')->name('pg');

// ============ admins ================
Route::get('/admin', 'TAdminController@index')->name('admin');
// admin task type
Route::get('/admin/tasktype', 'TAdminController@showTaskManagement')->name('admin.tt');
Route::post('/admin/addtt', 'TAdminController@doTaskMgmtAdd')->name('admin.addtt');
Route::post('/admin/deltt', 'TAdminController@disableTaskType')->name('admin.deltt');
// admin building
Route::get('/admin/build', 'TAdminController@buildingIndex')->name('admin.build');
Route::post('/admin/addbuild', 'TAdminController@addBuilding')->name('admin.addbuild');
Route::post('/admin/delbuild', 'TAdminController@delBuilding')->name('admin.delbuild');


// normal users
Route::get('/user', 'TStaffController@index')->name('staff');
Route::get('/user/task', 'TStaffController@taskIndex')->name('staff.t');


// bosses?
