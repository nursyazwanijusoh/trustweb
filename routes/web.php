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
Route::get('/adminlist', 'HomeController@listAdmins')->name('adminlist');
Route::get('/postreg', 'HomeController@postreg')->name('postreg');
Route::get('/resend', 'HomeController@resend')->name('verification.resend');
Route::get('/delete', 'HomeController@troll')->name('troll');
Route::get('/info', 'HomeController@info')->name('info');

Route::get('/booking_faq', 'HomeController@booking_faq')->name('booking_faq');

// ============ admins ================
Route::get('/admin', 'TAdminController@index')->name('admin');
// admin task type
Route::get('/admin/tasktype', 'TAdminController@showTaskManagement')->name('admin.tt');
Route::post('/admin/addtt', 'TAdminController@doTaskMgmtAdd')->name('admin.addtt');
Route::get('/admin/deltt', 'TAdminController@disableTaskType')->name('admin.deltt');
// admin activity type
Route::get('/admin/acttype', 'TAdminController@showActivityType')->name('admin.at');
Route::post('/admin/addat', 'TAdminController@doActivityTypeAdd')->name('admin.addat');
Route::get('/admin/delat', 'TAdminController@disableActivityType')->name('admin.delat');
// admin building
Route::get('/admin/build', 'TAdminController@buildingIndex')->name('admin.build');
Route::post('/admin/addbuild', 'TAdminController@addBuilding')->name('admin.addbuild')->middleware('SuperAdminGate');
Route::get('/admin/delbuild', 'TAdminController@delBuilding')->name('admin.delbuild')->middleware('SuperAdminGate');
Route::post('/admin/modbuild', 'TAdminController@modBuilding')->name('admin.modbuild');
Route::post('/admin/genseats', 'TAdminController@genSeats')->name('admin.genseats');
Route::get('/admin/getqr', 'TAdminController@getqr')->name('admin.getqr');
Route::get('/admin/delaseat', 'TAdminController@delaseat')->name('admin.delaseat');
Route::get('/admin/delallseat', 'TAdminController@delallseat')->name('admin.delallseat');
Route::get('/admin/buildetail', 'TAdminController@buildetail')->name('admin.buildetail');
Route::get('/admin/getallqr', 'TAdminController@getallqr')->name('admin.getallqr');
Route::get('/admin/seatToggle', 'TAdminController@seatToggle')->name('admin.seat.toggle');
Route::get('/admin/loadsap', 'SapLoadController@showSummaryPage')->name('admin.loadsapform');
Route::get('/admin/loadsapom', 'SapLoadController@processOM')->name('admin.processOM');
Route::get('/admin/loadsappersno', 'SapLoadController@mapPersNo')->name('admin.updatePersno');
Route::get('/admin/loadsapcuti', 'SapLoadController@loadDataCuti')->name('admin.loadDataCuti');

// meeting rooms
Route::get('/admin/meetroom', 'TAdminController@meetroom')->name('admin.meetroom');
Route::post('/admin/meetroom/add', 'TAdminController@meetroomAdd')->name('admin.meetroom.add');
Route::post('/admin/meetroom/edit', 'TAdminController@meetroomEdit')->name('admin.meetroom.edit');
Route::get('/admin/meetroom/del', 'TAdminController@meetroomDel')->name('admin.meetroom.del');

// admin allow access according to division
Route::get('/admin/allowdiv/{divid}', 'TAdminController@allowdiv')->name('admin.allowdiv');
Route::get('/admin/blockdiv/{divid}', 'TAdminController@blockdiv')->name('admin.blockdiv');
Route::post('/admin/reflov', 'TAdminController@refreshLOV')->name('admin.reflov');
Route::get('/admin/reflov', 'TAdminController@showLOV')->name('admin.lov');
Route::post('/admin/upstaffdiv', 'TAdminController@updateStaffDiv')->name('admin.upstaffdiv');


// admin staff role
Route::get('/admin/sr', 'TAdminController@showStaffRole')->name('admin.sr');
Route::post('/admin/addsr', 'TAdminController@assignRole')->name('admin.addsr');
Route::get('/admin/staff', 'TAdminController@blankStaff')->name('admin.st');
Route::get('/admin/list', 'TAdminController@listAdmin')->name('admin.list');
Route::post('/admin/editst', 'TAdminController@updateUser')->name('admin.upst')->middleware('SuperAdminGate');
Route::post('/admin/findstaff', 'TAdminController@findStaff')->name('admin.findst');
Route::get('/admin/sharedskill', 'TAdminController@showSharedSkillset')->name('admin.sharedskill');

Route::get('/admin/genqr', 'TAdminController@genQR')->name('admin.genqrg');
Route::post('/admin/genqr', 'TAdminController@genQR')->name('admin.genqrp');
Route::get('/admin/vendorlist', 'TAdminController@reglist')->name('admin.reglist');
Route::get('/admin/regapprove', 'TAdminController@regapprove')->name('admin.regapprove');
Route::post('/admin/regreject', 'TAdminController@regreject')->name('admin.regreject');
Route::get('/admin/delstaff', 'TAdminController@delstaff')->name('admin.delstaff');

Route::get('/admin/loadji', 'TAdminController@loadji')->name('admin.loadji');
Route::post('/admin/loadji', 'TAdminController@doloadji')->name('admin.doloadji');
Route::get('/admin/DlSampleJi', 'TAdminController@dlji')->name('admin.dlji');

// normal users
Route::get('/user', 'TStaffController@index')->name('staff');
Route::get('/user/verify/{token}', 'VerifyUserController@verify')->name('staff.verify');
Route::get('/user/task', 'TStaffController@taskIndex')->name('staff.t');
Route::get('/user/taskdetail', 'TStaffController@taskDetail')->name('staff.tdetail');
Route::post('/user/addtask', 'TStaffController@addTask')->name('staff.addtask');
Route::post('/user/closetask', 'TStaffController@closeTask')->name('staff.closetask');
// Route::get('/user/addactivity', 'TStaffController@addActivity')->name('staff.addact');
// Route::post('/user/doaddactivity', 'TStaffController@doAddACtivity')->name('staff.doaddact');
Route::get('/user/lochist', 'TStaffController@locHistory')->name('staff.lochist');

// GWD V2

Route::get('/user/activity', 'GwdActivityController@form')->name('staff.addact');
Route::post('/user/addactivity', 'GwdActivityController@add')->name('staff.doaddact');
Route::post('/user/dekactivity', 'GwdActivityController@delete')->name('staff.delact');
Route::post('/user/editactivity', 'GwdActivityController@edit')->name('staff.editact');
Route::get('/user/activity/info', 'GwdActivityController@actinfo')->name('staff.act.info');
Route::get('/user/activity/dayinfo', 'GwdActivityController@actdayinfo')->name('staff.act.dayinfo');
Route::get('/user/activity/list', 'GwdActivityController@list')->name('staff.list');
Route::post('/user/addleave', 'GwdActivityController@cuti')->name('staff.cuti');

Route::get('/reports/gwd/summary', 'GwdReportController@summary')->name('report.gwd.summary');
// Route::post('/reports/gwd/summary', 'GwdReportController@summaryres')->name('report.gwd.summaryres');
Route::get('/reports/gwd/entrystat', 'GwdReportController@entrystat')->name('report.gwd.entrystat');
Route::post('/reports/gwd/entrystat', 'GwdReportController@entrystatres')->name('report.gwd.entrystatres');
Route::get('/reports/gwd/detail', 'GwdReportController@detail')->name('report.gwd.detail');
// Route::post('/reports/gwd/detail', 'GwdReportController@detailres')->name('report.gwd.detailres');
// Route::get('/reports/gwd/gsum', 'GwdReportController@grpSummary')->name('reports.gwd.gsum');
// Route::post('/reports/gwd/gsum', 'GwdReportController@doGrpSummary')->name('reports.gwd.dogsum');

// bosses?
Route::get('/reports', 'ReportController@index')->name('reports');
Route::get('/reports/regstat', 'ReportController@registeredUser')->name('reports.regstat');
Route::get('/reports/floorutil', 'ReportController@floorAvailability')->name('reports.floorutil');
Route::get('/reports/depts', 'ReportController@showDepts')->name('reports.depts');
Route::get('/reports/workhour', 'ReportController@manDaysDispf')->name('reports.workhour');
Route::post('/reports/workhour', 'ReportController@manDaysDispf')->name('reports.workhourf');
Route::get('/reports/staffdayrpt', 'ReportController@staffDayRptSearch')->name('reports.staff.drs');
Route::get('/reports/staffspecificdayrpt', 'ReportController@staffSpecificDayRptSearch')->name('reports.staff.sdrs');
Route::get('/reports/floorutildetail', 'ReportController@floorUtilDetail')->name('reports.fud');
Route::post('/reports/floorutildetailr', 'ReportController@floorUtilDetailRes')->name('reports.fudr');
Route::get('/reports/divcheckin', 'ReportController@checkinByDiv')->name('reports.divcheckin');





// hot desking reports
Route::get('/hdreports/DivByDateFind', 'AdminReportController@DivByDateFind')->name('hdreports.dbdf');
Route::get('/hdreports/WorkSpaceUsage', 'AdminReportController@WorkSpaceUsage')->name('hdreports.wsu');
Route::get('/find/staff', 'AdminReportController@rptFindStaff')->name('staff.find');

// feedback
Route::get('/feedback', 'FeedbackController@sform')->name('feedback');
Route::post('/feedback', 'FeedbackController@submit')->name('feedback.submit');
Route::get('/feedback/close', 'FeedbackController@close')->name('feedback.close')->middleware('AdminGate');
Route::get('/feedback/list', 'FeedbackController@list')->name('feedback.list')->middleware('AdminGate');
Route::post('/feedback/doclose', 'FeedbackController@doclose')->name('feedback.doclose')->middleware('AdminGate');

// partners
Route::get('/partner/list', 'PartnerController@list')->name('partner.list');
Route::post('/partner/add', 'PartnerController@add')->name('partner.add');
Route::get('/partner/del', 'PartnerController@del')->name('partner.del');
Route::post('/partner/edit', 'PartnerController@edit')->name('partner.edit');

// configs
Route::get('/cfg/list', 'CommonConfigController@list')->name('cfg.list');
Route::post('/cfg/add', 'CommonConfigController@addedit')->name('cfg.add');
Route::post('/cfg/edit', 'CommonConfigController@edit')->name('cfg.edit');
Route::get('/cfg/del', 'CommonConfigController@del')->name('cfg.del');

// avatars
Route::get('/avatar/list', 'AvatarController@list')->name('avatar.list');
Route::post('/avatar/add', 'AvatarController@addedit')->name('avatar.add');
Route::get('/avatar/del', 'AvatarController@del')->name('avatar.del');

// coordinate
Route::get('/geo/list', 'OfficeController@list')->name('geo.list');
Route::post('/geo/add', 'OfficeController@addedit')->name('geo.add');
Route::post('/geo/edit', 'OfficeController@edit')->name('geo.edit');
Route::get('/geo/del', 'OfficeController@del')->name('geo.del');

// skill cat
Route::get('/skillcat/list', 'SkillCategoryController@list')->name('sc.list');
Route::post('/skillcat/add', 'SkillCategoryController@addedit')->name('sc.add');
Route::post('/skillcat/edit', 'SkillCategoryController@edit')->name('sc.edit');
Route::get('/skillcat/del', 'SkillCategoryController@del')->name('sc.del');

// public holiday
Route::get('/ph/list', 'PublicHolidayController@list')->name('ph.list');
Route::post('/ph/add', 'PublicHolidayController@add')->name('ph.add');
Route::post('/ph/edit', 'PublicHolidayController@edit')->name('ph.edit');
Route::get('/ph/del', 'PublicHolidayController@del')->name('ph.del');

// SAP leave type
Route::get('/leave/list', 'LeaveTypeController@list')->name('leave.list');
Route::post('/leave/add', 'LeaveTypeController@add')->name('leave.add');
Route::post('/leave/edit', 'LeaveTypeController@edit')->name('leave.edit');
Route::get('/leave/del', 'LeaveTypeController@del')->name('leave.del');

// common / shared skillset
Route::get('/sharedskill/list', 'SkillCategoryController@sslist')->name('ss.list');
Route::post('/sharedskill/add', 'SkillCategoryController@ssaddedit')->name('ss.add');
Route::post('/sharedskill/edit', 'SkillCategoryController@ssedit')->name('ss.edit');
Route::get('/sharedskill/del', 'SkillCategoryController@ssdel')->name('ss.del');

// personal skillset
Route::get('/user/skillset', 'PersonalSSController@list')->name('ps.list');
Route::post('/user/skillset/update', 'PersonalSSController@update')->name('ps.update');
Route::post('/user/skillset/create', 'PersonalSSController@createcustom')->name('ps.create');
Route::get('/user/skillset/addcustom', 'PersonalSSController@addcustom')->name('ps.add');
Route::get('/user/skillset/doaddcustom', 'PersonalSSController@doaddcustom')->name('ps.do.add');
Route::get('/user/skillset/del', 'PersonalSSController@delete')->name('ps.del');


// mobile app installers
Route::get('/download', 'AppDownloadController@list')->name('app.list');
Route::post('/app/upload', 'AppDownloadController@upload')->name('app.up');
Route::get('/app/get', 'AppDownloadController@download')->name('app.down');
Route::get('/app/get/trust.ipa', 'AppDownloadController@getipa')->name('app.ios');
Route::get('/app/get/trust.plist', 'AppDownloadController@getplist')->name('app.ios.plist');
Route::get('/app/delete', 'AppDownloadController@delete')->name('app.del');

// dashboard data feed thingy
Route::get('/dash', 'DashboardDataController@index')->name('dash.index');
Route::post('/dash/fetch', 'DashboardDataController@fetch')->name('dash.fetch');

// mobile only
Route::get('/feedback/mobile', 'FeedbackController@mobform')->name('feedback.mobile');
Route::get('/reg/mobile', 'HomeController@mobregform')->name('reg.mobile');

// discussion area events
Route::get('/area/list', 'AreaEventController@index')->name('area.list');
Route::get('/area/areacal', 'AreaEventController@areaEventCalendar')->name('area.cal');
Route::get('/area/eventdetail', 'AreaEventController@areaEventDetail')->name('area.evdetail');
Route::post('/area/addevent', 'AreaEventController@addEvent')->name('area.addevent');
Route::get('/area/myevents', 'AreaEventController@myevents')->name('area.myevents');
Route::post('/area/cancelevent', 'AreaEventController@cancelEvent')->name('area.cancelevent');
Route::post('/area/rejectevent', 'AreaEventController@rejectEvent')->name('area.rejectevent');

// public holiday
Route::get('/cgrp/list', 'CompGroupController@list')->name('cgrp.list');
Route::post('/cgrp/add', 'CompGroupController@add')->name('cgrp.add');
Route::post('/cgrp/edit', 'CompGroupController@edit')->name('cgrp.edit');
Route::post('/cgrp/del', 'CompGroupController@del')->name('cgrp.del');
Route::get('/cgrp/view', 'CompGroupController@view')->name('cgrp.view');
Route::post('/cgrp/take', 'CompGroupController@take')->name('cgrp.take');
Route::post('/cgrp/remove', 'CompGroupController@remove')->name('cgrp.remove');

// blast notifications
Route::get('/pushalert', 'PushAnnouncementController@showForm')->name('pn.form');
Route::post('/pushalert/send', 'PushAnnouncementController@registerReq')->name('pn.reg');
Route::get('/pushalert/getstatus', 'PushAnnouncementController@doSend')->name('pn.dosend');
