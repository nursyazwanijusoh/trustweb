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
Route::get('/policy', 'HomeController@policy')->name('policy');
Route::get('/pg', 'HomeController@playground')->name('pg');
Route::get('/getaddress', 'WebApiController@reverseGeo')->name('reversegeo');
Route::get('/staff/image', 'WebApiController@getImage')->name('staff.image');
Route::get('/adminlist', 'HomeController@listAdmins')->name('adminlist');
Route::get('/postreg', 'HomeController@postreg')->name('postreg');
Route::get('/resend', 'HomeController@resend')->name('verification.resend');
Route::get('/delete', 'HomeController@troll')->name('troll');
Route::get('/info', 'HomeController@info')->name('info');
Route::get('/hallofflame', 'HomeController@hallofshame')->name('hofs')->middleware('auth');
Route::get('/hallofflame/staff', 'HomeController@staffFancyReport')->name('phofs')->middleware('auth');
Route::get('/guides', 'HomeController@guides')->name('guides');

Route::get('/booking_faq', 'HomeController@booking_faq')->name('booking_faq');
Route::get('/readnotify', 'NotiMenuController@read')->name('notify.read');

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
Route::get('/admin/usercompare', 'TAdminController@CompareStaffData')->name('admin.usercompare');
Route::post('/admin/updateuserdata', 'TAdminController@UpdateStaffData')->name('admin.updateuserdata');
Route::get('/admin/delaseat', 'TAdminController@delaseat')->name('admin.delaseat');
Route::get('/admin/delallseat', 'TAdminController@delallseat')->name('admin.delallseat');
Route::get('/admin/buildetail', 'TAdminController@buildetail')->name('admin.buildetail');
Route::get('/admin/getallqr', 'TAdminController@getallqr')->name('admin.getallqr');
Route::get('/admin/seatToggle', 'TAdminController@seatToggle')->name('admin.seat.toggle');
Route::get('/admin/loadsap', 'SapLoadController@showSummaryPage')->name('admin.loadsapform');
Route::post('/admin/loadsap', 'SapLoadController@uploadTeamAB')->name('admin.uploadTeamAB');
Route::get('/admin/loadsapom', 'SapLoadController@processOM')->name('admin.processOM');
Route::get('/admin/loadkemahiran', 'SapLoadController@loadBulkSkillset')->name('admin.loadDataSkill');
Route::get('/admin/loadsapcuti', 'SapLoadController@loadDataCuti')->name('admin.loadDataCuti');

// 8 jam jumaat untuk webe
Route::get('/admin/fridayhours', 'AdminReportController@fridayulist')->name('admin.fridayhours');
Route::post('/admin/fridayhours/add', 'AdminReportController@addfriday8')->name('admin.addfriday8');
Route::post('/admin/fridayhours/del', 'AdminReportController@delfriday8')->name('admin.delfriday8');

// guides
Route::get('/admin/guides', 'GuideController@list')->name('admin.guides');
Route::post('/admin/guides/add', 'GuideController@add')->name('admin.addguide');
Route::post('/admin/guides/del', 'GuideController@delete')->name('admin.delguide');

// meeting rooms
Route::get('/admin/meetroom', 'TAdminController@meetroom')->name('admin.meetroom');
Route::post('/admin/meetroom/add', 'TAdminController@meetroomAdd')->name('admin.meetroom.add');
Route::post('/admin/meetroom/edit', 'TAdminController@meetroomEdit')->name('admin.meetroom.edit');
Route::get('/admin/meetroom/del', 'TAdminController@meetroomDel')->name('admin.meetroom.del');

// announcement
Route::get('/admin/announcement', 'AnnouncementController@list')->name('admin.annc');
Route::post('/admin/announcement/add', 'AnnouncementController@add')->name('admin.annc.add');
Route::post('/admin/announcement/edit', 'AnnouncementController@edit')->name('admin.annc.edit');
Route::get('/admin/announcement/del', 'AnnouncementController@delete')->name('admin.annc.del');

// admin allow access according to division
Route::get('/admin/allowdiv/{divid}', 'TAdminController@allowdiv')->name('admin.allowdiv');
Route::get('/admin/blockdiv/{divid}', 'TAdminController@blockdiv')->name('admin.blockdiv');
Route::post('/admin/reflov', 'TAdminController@refreshLOV')->name('admin.reflov');
Route::get('/admin/reflov', 'TAdminController@showLOV')->name('admin.lov');
Route::post('/admin/upstaffdiv', 'TAdminController@updateStaffDiv')->name('admin.upstaffdiv');

// admin bulk update
Route::get('/admin/bulkupdate', 'BatchJobController@menu')->name('admin.bulkupdate');
Route::get('/admin/findBand45', 'BatchJobController@findBand45')->name('admin.findBand45');


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

// MCO - WFH initiative
Route::get('/mco/reqform', 'McoTravelReqController@reqform')->name('mco.reqform');
Route::post('/mco/submitform', 'McoTravelReqController@submitform')->name('mco.submitform');
Route::get('/mco/ackreqs', 'McoTravelReqController@ackreqs')->name('mco.ackreqs');
Route::post('/mco/takeaction', 'McoTravelReqController@takeaction')->name('mco.takeaction');
Route::post('/mco/takeactionall', 'McoTravelReqController@takeactionall')->name('mco.takeactionall');
Route::get('/mco/checkins', 'McoTravelReqController@checkins')->name('mco.checkins');
Route::get('/mco/getpermit', 'McoTravelReqController@getpermit')->name('mco.getpermit');
Route::get('/admin/mcorpt', 'TAdminTwoController@McoReport')->name('mco.rpt');

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

// clockins
Route::get('/user/location', 'LocationHistoryController@list')->name('clock.list');
Route::post('/user/location/cekin', 'LocationHistoryController@clockin')->name('clock.in');

// GWD V2

Route::get('/user/activity', 'GwdActivityController@form')->name('staff.addact');
Route::post('/user/addactivity', 'GwdActivityController@add')->name('staff.doaddact');
Route::post('/user/dekactivity', 'GwdActivityController@delete')->name('staff.delact');
Route::post('/user/editactivity', 'GwdActivityController@edit')->name('staff.editact');
Route::get('/user/activity/info', 'GwdActivityController@actinfo')->name('staff.act.info');
Route::get('/user/activity/dayinfo', 'GwdActivityController@actdayinfo')->name('staff.act.dayinfo');
Route::get('/user/activity/list', 'GwdActivityController@list')->name('staff.list');
Route::post('/user/addleave', 'GwdActivityController@cuti')->name('staff.cuti');

Route::get('/reports/gwd/gsummary', 'GwdReportController@summary')->name('report.gwd.summary');
Route::get('/reports/gwd/personal', 'GwdReportController@getPersonalApi')->name('report.gwd.api.person');
Route::post('/reports/gwd/gsummdl', 'GwdReportController@dlgsummary')->name('report.gwd.gsummdl');
// Route::post('/reports/gwd/summary', 'GwdReportController@summaryres')->name('report.gwd.summaryres');
Route::get('/reports/gwd/divsummary', 'GwdReportController@divsum')->name('report.gwd.divsum');
Route::post('/reports/gwd/entrystat', 'GwdReportController@entrystatres')->name('report.gwd.entrystatres');
Route::get('/reports/gwd/detail', 'GwdReportController@detail')->name('report.gwd.detail');
Route::get('/reports/gwd/detailexport', 'GwdReportController@detailexport')->name('report.gwd.detailexport')->middleware('AdminGate');
Route::get('/reports/gwd/grpanalysis', 'GwdReportController@grpanalysis')->name('report.gwd.grpanalysis')->middleware('AdminGate');
// Route::post('/reports/gwd/detail', 'GwdReportController@detailres')->name('report.gwd.detailres');
// Route::get('/reports/gwd/gsum', 'GwdReportController@grpSummary')->name('reports.gwd.gsum');
// Route::post('/reports/gwd/gsum', 'GwdReportController@doGrpSummary')->name('reports.gwd.dogsum');
Route::get('/reports/teamproductivity', 'GwdReportController@agmrecent')->name('report.agm.recent');
Route::get('/reports/teamlocations', 'GwdReportController@teamlocs')->name('report.team.locations');
Route::get('/reports/loc/personal', 'WebApiController@getPersonalLocApi')->name('report.loc.api.person');
Route::get('/reports/cekin/personal', 'WebApiController@getPersonalCheckApi')->name('report.cekin.api.person');
Route::get('/reports/checkin/overall', 'ReportController@checkinByFloorDiv')->name('reports.c.overall');
Route::get('/reports/checkin/detail', 'ReportController@checkinByTeamDetail')->name('reports.c.detail');
Route::get('/reports/webapi/floorsum', 'WebApiController@getFloorCheckinSummary')->name('reports.api.floorsum');
Route::get('/reports/webapi/indiarept', 'WebApiController@indivDetailRept')->name('reports.api.indiarept');
Route::get('/reports/webapi/indianal', 'WebApiController@indivDiaryAnalysis')->name('reports.api.indianal');

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

// cuti v2 - zerorize expected hours
Route::get('/manualleave', 'StaffLeaveController@list')->name('mleave.list');
Route::post('/manualleave/add', 'StaffLeaveController@add')->name('mleave.add');
Route::post('/manualleave/del', 'StaffLeaveController@del')->name('mleave.del');
Route::post('/manualleave/reflag', 'TAdminTwoController@leaveFlag')->name('mleave.reflag');



// hot desking reports
Route::get('/hdreports/DivByDateFind', 'AdminReportController@DivByDateFind')->name('hdreports.dbdf');
Route::get('/hdreports/WorkSpaceUsage', 'AdminReportController@WorkSpaceUsage')->name('hdreports.wsu');
Route::get('/find/staff', 'TStaffController@rptFindStaff')->name('staff.find');
Route::post('/find/skill', 'TStaffController@rptFindStaffWSkill2')->name('staff.skill.find');

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

// skill type
Route::get('/skilltype/list', 'SkillCategoryController@stlist')->name('st.list');
Route::post('/skilltype/add', 'SkillCategoryController@staddedit')->name('st.add');
Route::post('/skilltype/edit', 'SkillCategoryController@stedit')->name('st.edit');
Route::get('/skilltype/del', 'SkillCategoryController@stdel')->name('st.del');

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
Route::get('/sharedskill/staffs', 'SkillCategoryController@staffWithSkill')->name('ss.staffs');

// bau / exp
Route::get('/bauexp/list', 'BauExperienceController@list')->name('bauexp.list');
Route::post('/bauexp/add', 'BauExperienceController@add')->name('bauexp.add');
Route::post('/bauexp/edit', 'BauExperienceController@edit')->name('bauexp.edit');
Route::get('/bauexp/del', 'BauExperienceController@del')->name('bauexp.del');
Route::get('/bauexp/staffs', 'BauExperienceController@staffWithExp')->name('bauexp.staffs');

Route::get('/bauexp/role/list', 'JobscopeController@list')->name('bauexp.role.list');
Route::post('/bauexp/role/add', 'JobscopeController@add')->name('bauexp.role.add');
Route::post('/bauexp/role/edit', 'JobscopeController@edit')->name('bauexp.role.edit');
Route::get('/bauexp/role/del', 'JobscopeController@del')->name('bauexp.role.del');

// news
Route::get('/admin/news', 'NewsController@list')->name('admin.news.list');
Route::post('/admin/news/add', 'NewsController@add')->name('admin.news.add');
Route::post('/admin/news/del', 'NewsController@del')->name('admin.news.del');
Route::get('/admin/news/api/detail', 'NewsController@detail')->name('admin.news.api.detail');
Route::get('/news', 'HomeController@news')->name('news');


// personal skillset and experience
Route::get('/user/skillset', 'PersonalSSController@listv2')->name('ps.list');
Route::get('/user/experience', 'PersonalSSController@listExpage')->name('ps.exps');
Route::post('/user/skillset/add', 'PersonalSSController@updatev2')->name('ps.update');
Route::post('/user/skillset/mod', 'PersonalSSController@modify')->name('ps.mod');
Route::get('/user/skillset/detail', 'PersonalSSController@detail')->name('ps.detail');
Route::post('/user/skillset/addexp', 'PersonalSSController@addexp')->name('ps.addexp');
Route::post('/user/skillset/editexp', 'PersonalSSController@editexp')->name('ps.editexp');
Route::post('/user/skillset/delexp', 'PersonalSSController@delexp')->name('ps.delexp');
Route::get('/user/skillset/pendingapprove', 'PersonalSSController@pendingapprove')->name('ps.pendingapprove');

// temp Skillset APIs
Route::get('/ss/api/gettype', 'WebApiController@SSApiGetType')->name('ss.api.gettype');
Route::get('/ss/api/getskill', 'WebApiController@SSApiGetSkill')->name('ss.api.getskill');

Route::get('/webapi/findstaff', 'WebApiController@findstaff')->name('webapi.findstaff');
Route::get('/webapi/s2findstaff', 'WebApiController@select2FindStaff')->name('webapi.s2findstaff');

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
Route::post('/cgrp/removerep', 'CompGroupController@removerep')->name('cgrp.removerep');
Route::post('/cgrp/addrep', 'CompGroupController@addrep')->name('cgrp.addrep');

// blast notifications
Route::get('/pushalert', 'PushAnnouncementController@showForm')->name('pn.form');
Route::post('/pushalert/send', 'PushAnnouncementController@registerReq')->name('pn.reg');
Route::get('/pushalert/getstatus', 'PushAnnouncementController@doSend')->name('pn.dosend');

// polls
Route::get('/polls', 'PollController@index')->name('poll.index');
Route::get('/polls/my', 'PollController@mypolls')->name('poll.my');
Route::get('/polls/create', 'PollController@createPoll')->name('poll.create');
Route::post('/polls/docreate', 'PollController@docreatePoll')->name('poll.docreate');
Route::post('/polls/publish', 'PollController@publishPoll')->name('poll.publish');
Route::post('/polls/delete', 'PollController@deletePoll')->name('poll.delete');
Route::post('/polls/addopt', 'PollController@addOption')->name('poll.addopt');
Route::post('/polls/remopt', 'PollController@removeOption')->name('poll.remopt');
Route::get('/polls/view', 'PollController@viewPoll')->name('poll.view');
Route::post('/polls/vote', 'PollController@vote')->name('poll.vote');

// art mgmt
Route::get('/art', 'AgileResourceTeamController@list')->name('art.list');
Route::post('/art/add', 'AgileResourceTeamController@add')->name('art.add');
Route::post('/art/del', 'AgileResourceTeamController@del')->name('art.del');


//tribe
//Route::get('/tribe/home', 'TribeController@home')->name('tribe.home');
Route::get('/tribe/home', 'TribeController@validateToken')->name('tribe.validateToken');
Route::get('/tribe/vt', 'TribeController@vt')->name('tribe.vt');



//smile
Route::get('/smile', 'SmileController@index')->name('smile');
Route::get('/smile/form', 'SmileController@form')->name('smile.form');
Route::post('/smile/submit', 'SmileController@submit')->name('smile.submit');
