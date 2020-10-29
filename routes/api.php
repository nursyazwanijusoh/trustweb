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
  $api->get('/pg',            ['as' => 'api.pg',   'uses' => 'App\Api\V1\Controllers\Controller@playground']);
  $api->get('/alert',            ['as' => 'api.alert',   'uses' => 'App\Api\V1\Controllers\UserController@testNotify']);
  $api->get('/news',            ['as' => 'api.alert',   'uses' => 'App\Api\V1\Controllers\MiscController@GetNews']);


  // actually used
  $api->get('/CCGet',  ['as' => 'lov.cc.get', 'uses' => 'App\Api\V1\Controllers\LovController@ccGet']);

  // BATCH
  $api->get('/massKickOut',  ['as' => 'check.kick.all', 'uses' => 'App\Api\V1\Controllers\InfraController@massKickOut']);
  $api->get('/reserveExpired',  ['as' => 'reserve.expired', 'uses' => 'App\Api\V1\Controllers\InfraController@reserveExpired']);
  $api->get('/GwdCreateDaily',  ['as' => 'gwd.create.daily.perf', 'uses' => 'App\Api\V1\Controllers\BatchController@GwdCreateDayPerf']);
  $api->get('/SendDiaryReminder',  ['as' => 'gwd.send.diary.reminder', 'uses' => 'App\Api\V1\Controllers\BatchController@SendDiaryReminder']);
  $api->get('/loadEmplProfile',  ['as' => 'sap.load.prof', 'uses' => 'App\Api\V1\Controllers\BatchController@loadEmplProfile']);
  $api->get('/loadEmplLeave',  ['as' => 'sap.load.cuti', 'uses' => 'App\Api\V1\Controllers\BatchController@loadEmplLeave']);

  // ==========================
  // might not even be used lol

  // inventory management
  // $api->post('/buildingCreate',  ['as' => 'build.c', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingCreate']);
  // $api->post('/buildingSearch',  ['as' => 'build.r', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingSearch']);
  // $api->post('/buildingEdit',    ['as' => 'build.u', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingEdit']);
  // $api->post('/buildingDelete',  ['as' => 'build.d', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingDelete']);
  //
  // $api->post('/seatCreate',  ['as' => 'seat.c', 'uses' => 'App\Api\V1\Controllers\InfraController@seatCreate']);
  // $api->post('/seatSearch',  ['as' => 'seat.r', 'uses' => 'App\Api\V1\Controllers\InfraController@seatSearch']);
  // $api->post('/seatEdit',    ['as' => 'seat.u', 'uses' => 'App\Api\V1\Controllers\InfraController@seatEdit']);
  // $api->post('/seatDelete',  ['as' => 'seat.d', 'uses' => 'App\Api\V1\Controllers\InfraController@seatDelete']);


  // admin->staff kind of thingy
  $api->post('/AdminAddStaff',  ['as' => 'admin.as', 'uses' => 'App\Api\V1\Controllers\AdminController@adminAddStaff']);
  $api->post('/AdminUpdateStaff',  ['as' => 'admin.ups', 'uses' => 'App\Api\V1\Controllers\AdminController@AdminUpdateStaff']);
  // $api->post('/UserLogin',  ['as' => 'user.login', 'uses' => 'App\Api\V1\Controllers\AdminController@doLogin']);

  // LOV management
  // $api->post('/LovTaskTypeAdd',  ['as' => 'lov.tt.c', 'uses' => 'App\Api\V1\Controllers\LovController@ttCreate']);
  // $api->post('/LovTaskTypeSearch',  ['as' => 'lov.tt.r', 'uses' => 'App\Api\V1\Controllers\LovController@ttSearch']);
  // $api->post('/LovTaskTypeEdit',  ['as' => 'lov.tt.u', 'uses' => 'App\Api\V1\Controllers\LovController@ttEdit']);
  // $api->post('/LovTaskTypeDelete',  ['as' => 'lov.tt.d', 'uses' => 'App\Api\V1\Controllers\LovController@ttDelete']);
  //
  // $api->post('/LovActTypeAdd',  ['as' => 'lov.at.c', 'uses' => 'App\Api\V1\Controllers\LovController@atCreate']);
  // $api->post('/LovActTypeSearch',  ['as' => 'lov.at.r', 'uses' => 'App\Api\V1\Controllers\LovController@atSearch']);
  // $api->post('/LovActTypeEdit',  ['as' => 'lov.at.u', 'uses' => 'App\Api\V1\Controllers\LovController@atEdit']);
  // $api->post('/LovActTypeDelete',  ['as' => 'lov.at.d', 'uses' => 'App\Api\V1\Controllers\LovController@atDelete']);



  // activities
  $api->post('/UserLogin',  ['as' => 'user.login', 'uses' => 'App\Api\V1\Controllers\LoginController@doLogin']);
  $api->post('/UserJustLogin',  ['as' => 'user.j.login', 'uses' => 'App\Api\V1\Controllers\LoginController@justLogin']);

  $api->post('/ReserveSeat',  ['as' => 'user.reserve', 'uses' => 'App\Api\V1\Controllers\UserController@ReserveSeatV2']);
  $api->post('/ReserveCancel',  ['as' => 'user.reservec', 'uses' => 'App\Api\V1\Controllers\UserController@ReserveCancel']);
  $api->post('/CheckinFromReserve',  ['as' => 'user.checkin.res', 'uses' => 'App\Api\V1\Controllers\UserController@CheckinFromReserve']);

  // get infos
  $api->post('/buildingListSeats',  ['as' => 'build.listseat', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingListSeats']);

  // GWD stuffs
  $api->post('/gwd/add',  ['as' => 'api.gwd.add', 'uses' => 'App\Api\V1\Controllers\MiscController@GwdAddActivity']);
  $api->post('/gwd/summary',  ['as' => 'api.gwd.sum', 'uses' => 'App\Api\V1\Controllers\MiscController@GwdGetSummary']);
  $api->get('/gwd/acttype',  ['as' => 'api.gwd.type', 'uses' => 'App\Api\V1\Controllers\MiscController@GwdGetActType']);
  $api->get('/gwd/actcat',  ['as' => 'api.gwd.cat', 'uses' => 'App\Api\V1\Controllers\MiscController@GwdGetActCat']);
  $api->post('/gwd/edit',  ['as' => 'api.gwd.cat', 'uses' => 'App\Api\V1\Controllers\MiscController@GwdEditActivity']);
  $api->post('/gwd/delete',  ['as' => 'api.gwd.cat', 'uses' => 'App\Api\V1\Controllers\MiscController@GwdDelActivity']);
  $api->post('/gwd/listacts',  ['as' => 'api.gwd.cat', 'uses' => 'App\Api\V1\Controllers\MiscController@GwdGetActivities']);
  $api->post('/gwd/monthsummary',  ['as' => 'api.gwd.msum', 'uses' => 'App\Api\V1\Controllers\MiscController@GwdGetMonthSummary']);
  $api->post('/gwd/monthcaldot',  ['as' => 'api.gwd.mcaldot', 'uses' => 'App\Api\V1\Controllers\UserController@getMonthlyCalDots']);
  $api->post('/team/locations',  ['as' => 'api.rpt.getteamloc', 'uses' => 'App\Api\V1\Controllers\UserController@getTeamLocation']);
  $api->post('/team/performance',  ['as' => 'api.rpt.getteamperf', 'uses' => 'App\Api\V1\Controllers\TeamController@GetTeamAvgPerf']);



  // to be disabled once all tokens api go live
  $api->post('/buildingGetSummary',  ['as' => 'build.summary', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingGetSummary']);
  $api->get('/buildingAllSummary',  ['as' => 'build.allsummary', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingAllSummary']);
  $api->post('/UserGetInfo',  ['as' => 'user.info', 'uses' => 'App\Api\V1\Controllers\UserController@getCustInfo']);
  $api->post('/GiveFeedback',  ['as' => 'api.fb.submit', 'uses' => 'App\Api\V1\Controllers\MiscController@sendFeedback']);
  $api->post('/CheckOut',  ['as' => 'user.checkout', 'uses' => 'App\Api\V1\Controllers\UserController@CheckOut']);
  $api->post('/seatScanQR',  ['as' => 'seat.qr', 'uses' => 'App\Api\V1\Controllers\InfraController@seatScanQR']);
  $api->post('/CheckinDirect',  ['as' => 'user.checkin.dir', 'uses' => 'App\Api\V1\Controllers\UserController@CheckinDirect']);
  $api->post('/UserFind',  ['as' => 'user.finda', 'uses' => 'App\Api\V1\Controllers\UserController@Find']);
  $api->post('/UserListBuilding',  ['as' => 'user.listbuild', 'uses' => 'App\Api\V1\Controllers\UserController@ListAllowedBuilding']);

  $api->post('/UserClockIn',  ['as' => 'user.clockin', 'uses' => 'App\Api\V1\Controllers\UserController@clockIn']);
  $api->post('/UserClockOut',  ['as' => 'user.clockout', 'uses' => 'App\Api\V1\Controllers\UserController@clockOut']);
  $api->post('/UserUpdateLoc',  ['as' => 'user.updateloc', 'uses' => 'App\Api\V1\Controllers\UserController@updateLocation']);
  $api->post('/UserLocHistory',  ['as' => 'user.lochistory', 'uses' => 'App\Api\V1\Controllers\UserController@getLocHistory']);

  $api->get('/officeGetBuilding',  ['as' => 'office.b.getbytype', 'uses' => 'App\Api\V1\Controllers\InfraController@getOfficeBuilding']);
  $api->get('/officeGetFloor',  ['as' => 'office.f.getbytype', 'uses' => 'App\Api\V1\Controllers\InfraController@getOfficeFloor']);
  $api->get('/officeGetArea',  ['as' => 'office.area.getbytype', 'uses' => 'App\Api\V1\Controllers\InfraController@getOfficeArea']);

  $api->post('/UserGetGwdRank',  ['as' => 'user.getGwdRank', 'uses' => 'App\Api\V1\Controllers\UserController@getGwdRank']);

  $api->post('/SeatReq',  ['as' => 'seat.request', 'uses' => 'App\Api\V1\Controllers\UserController@requestSeatAccess']);
  $api->post('/SeatReqDeny',  ['as' => 'seat.deny', 'uses' => 'App\Api\V1\Controllers\UserController@denySeatRequest']);
  $api->post('/SeatReqAccept',  ['as' => 'seat.req.accept', 'uses' => 'App\Api\V1\Controllers\UserController@acceptSeatRequest']);
  $api->post('/floorTomorrowStatus',  ['as' => 'infra.esok.status', 'uses' => 'App\Api\V1\Controllers\InfraController@getTomorrowAvailability']);


  // mco
  $api->post('/mco/getgminfo',  ['as' => 'api.mco.GetGmInfo', 'uses' => 'App\Api\V1\Controllers\McoController@GetGmInfo']);
  $api->post('/mco/requestmcoack',  ['as' => 'api.mco.requestMcoAck', 'uses' => 'App\Api\V1\Controllers\McoController@requestMcoAck']);
  $api->post('/mco/requestlist',  ['as' => 'api.mco.requestList', 'uses' => 'App\Api\V1\Controllers\McoController@requestList']);
  $api->get('/mco/getpermit',  ['as' => 'api.mco.getpermit', 'uses' => 'App\Api\V1\Controllers\McoController@getPermit']);
  $api->post('/mco/getpendingack',  ['as' => 'api.mco.getpending', 'uses' => 'App\Api\V1\Controllers\McoController@getpending']);
  $api->post('/mco/getapprovedack',  ['as' => 'api.mco.getapproved', 'uses' => 'App\Api\V1\Controllers\McoController@getapproved']);
  $api->post('/mco/getmcocheckin',  ['as' => 'api.mco.getmcocheckin', 'uses' => 'App\Api\V1\Controllers\McoController@getmcocheckin']);

});

$api->version('v1', [
  'middleware' => 'auth:api',
  'prefix' => 'api/t'
], function ($api) {
  $api->post('/pg',  ['as' => 'api.pg', 'uses' => 'App\Api\V1\Controllers\UserController@pg']);

  $api->post('/ValidateToken',  ['as' => 'api.validtoken', 'uses' => 'App\Api\V1\Controllers\UserController@validateToken']);

  $api->post('/buildingGetSummary',  ['as' => 'o.build.summary', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingGetSummary']);
  $api->get('/buildingAllSummary',  ['as' => 'o.build.allsummary', 'uses' => 'App\Api\V1\Controllers\InfraController@buildingAllSummary']);
  $api->post('/UserGetInfo',  ['as' => 'o.user.info', 'uses' => 'App\Api\V1\Controllers\UserController@getCustInfo']);
  $api->post('/GiveFeedback',  ['as' => 'o.api.fb.submit', 'uses' => 'App\Api\V1\Controllers\MiscController@sendFeedback']);
  $api->post('/CheckOut',  ['as' => 'o.user.checkout', 'uses' => 'App\Api\V1\Controllers\UserController@CheckOut']);
  $api->post('/seatScanQR',  ['as' => 'o.seat.qr', 'uses' => 'App\Api\V1\Controllers\InfraController@seatScanQR']);
  $api->post('/CheckinDirect',  ['as' => 'o.user.checkin.dir', 'uses' => 'App\Api\V1\Controllers\UserController@CheckinDirect']);
  $api->post('/UserFind',  ['as' => 'o.user.finda', 'uses' => 'App\Api\V1\Controllers\UserController@Find']);
  $api->post('/UserListBuilding',  ['as' => 'o.user.listbuild', 'uses' => 'App\Api\V1\Controllers\UserController@ListAllowedBuilding']);
  $api->post('/UserClockIn',  ['as' => 'o.user.clockin', 'uses' => 'App\Api\V1\Controllers\UserController@clockIn']);
  $api->post('/UserClockOut',  ['as' => 'o.user.clockout', 'uses' => 'App\Api\V1\Controllers\UserController@clockOut']);


  $api->get('/officeGetBuilding',  ['as' => 'office.b.getbytype', 'uses' => 'App\Api\V1\Controllers\InfraController@getOfficeBuilding']);
  $api->get('/officeGetFloor',  ['as' => 'office.f.getbytype', 'uses' => 'App\Api\V1\Controllers\InfraController@getOfficeFloor']);
  $api->get('/officeGetArea',  ['as' => 'office.area.getbytype', 'uses' => 'App\Api\V1\Controllers\InfraController@getOfficeArea']);

  // mco
  $api->post('/mco/takeaction',  ['as' => 'api.mco.takeaction', 'uses' => 'App\Api\V1\Controllers\McoController@takeaction']);
  $api->post('/mco/takeactionall',  ['as' => 'api.mco.takeactionall', 'uses' => 'App\Api\V1\Controllers\McoController@takeactionall']);

});


$api->version('v1', [
  'middleware' => 'auth:api',
  'prefix' => 'api/tribe'
], function ($api) {
  $api->post('/staffno',  ['as' => 'api.tribe.getDetails', 'uses' => 'App\Api\V1\Controllers\Tribe\UserController@getDetail']);
  $api->post('/userbyskillset',  ['as' => 'api.tribe.userbyskillset', 'uses' => 'App\Api\V1\Controllers\Tribe\SkillController@getUsersBySkills']);
  $api->post('/userbyskillset2',  ['as' => 'api.tribe.userbyskillset2', 'uses' => 'App\Api\V1\Controllers\Tribe\SkillController@getUsersBySkills2']);


});


$api->version('v1', ['prefix' => 'api/tribe',], function ($api) {
  $api->get('/vt',  ['as' => 'api.tribe.vt', 'uses' => 'App\Api\V1\Controllers\Tribe\UserController@validateToken']);
  $api->get('/skillset',  ['as' => 'api.tribe.skillset', 'uses' => 'App\Api\V1\Controllers\Tribe\SkillController@getSkills']);

});
