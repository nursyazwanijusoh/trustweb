<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\AdminGate;
use App\Mail\RegApproved;
use App\Mail\RegRejected;
use App\RejectedUser;
use App\TaskCategory;
use App\ActivityType;
use App\building;
use App\Office;
use App\place;
use App\User;
use App\Unit;
use App\SubUnit;
use App\Feedback;
use App\Checkin;
use App\common\UserRegisterHandler;
use Session;
use App\Api\V1\Controllers\LdapHelper;
use App\Api\V1\Controllers\BookingHelper;


class TAdminController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  // main admin page
  public function index(){

    $fbcount = Feedback::where('status', 1)->count();
    $prc = User::where('status', 2)->where('verified', true)->count();

    return view('admin.index', ['fbc' => $fbcount, 'prc' => $prc]);
    // return Session::get('staffdata')['role'];
  }

  // -------- task type Management ---------
  // list all current task, and a form to add new
  public function showTaskManagement(){
    $tasklist = TaskCategory::where('status', 1)->get();
    return view('admin.tasktype', ['currtasklist' => $tasklist]);
  }

  // add the submitted task type, then redirect back to showTaskManagement
  public function doTaskMgmtAdd(Request $req){
    $tt = new TaskCategory;
    $tt->descr = $req->descr;
    $tt->remark = $req->remark;
    $tt->status = 1;

    $tt->save();

    $tasklist = TaskCategory::where('status', 1)->get();
    return view('admin.tasktype', ['currtasklist' => $tasklist]);

  }

  // delete task type. more like, change the status to inactive
  public function disableTaskType(Request $req){
    $task = TaskCategory::where('id', $req->taskid)->first();
    $task->status = 0;
    $task->save();

    return redirect(route('admin.tt', [], false));
  }

  // ----- activity type management -----
  public function showActivityType(){
    $tasklist = ActivityType::where('status', 1)->get();
    return view('admin.activitytype', ['currtasklist' => $tasklist]);
  }

  // add the submitted task type, then redirect back to showTaskManagement
  public function doActivityTypeAdd(Request $req){
    $tt = new ActivityType;
    $tt->descr = $req->descr;
    $tt->remark = $req->remark;
    $tt->status = 1;

    $tt->save();

    $tasklist = ActivityType::where('status', 1)->get();
    return view('admin.activitytype', ['currtasklist' => $tasklist]);

  }

  // delete task type. more like, change the status to inactive
  public function disableActivityType(Request $req){
    $task = ActivityType::where('id', $req->taskid)->first();
    $task->status = 0;
    $task->save();

    return redirect(route('admin.at', [], false));
  }


  // ------- ROLES --------
  public function showStaffRole(Request $req){
    // get the list of buildings
    $blist = building::where('status', '1')->orderBy('unit', 'ASC')->get();
    $seldiv = Session::get('staffdata')['lob'];

    // get the registered list of division / unit
    $divrlist = \DB::table('users')
      ->select('lob', \DB::raw('count(*) as reg_count'))
      ->groupBy('lob')
      ->get();
    $divalist = [];

    // translate the div/unit
    foreach($divrlist as $adiv){
      $sel = '';
      $unit = Unit::where('pporgunit', $adiv->lob)->first();
      $unitname = $adiv->lob;  // default, just in case
      if($unit){
        $unitname = $unit->pporgunitdesc;
      }

      if($adiv->lob == $seldiv){
        $sel = 'selected';
      }

      array_push($divalist, [
        'pporgunit' => $adiv->lob,
        'divname' => $unitname,
        'regcount' => $adiv->reg_count,
        'sel' => $sel
      ]);
    }

    if($req->filled('alert')){
      return view('admin.assignrole', [
        'blist' => $blist, 'divlist' => $divalist,
        'alert' => $req->alert
      ]);
    } else {
      return view('admin.assignrole', ['blist' => $blist, 'divlist' => $divalist]);
    }
  }

  public function blankStaff(){
    $blist = building::where('status', '1')->orderBy('unit', 'ASC')->get();
    // add blank check status for each building
    foreach($blist as $build){
      $build['chk'] = '';
    }

    $myrole = Session::get('staffdata')['role'];

    // create empty staff data
    $staff = [
      'STAFF_NO' => '',
      'NAME' => '',
      'UNIT' => '',
      'SUBUNIT' => '',
      'DEPARTMENT' => '',
      'MOBILE_NO' => '',
      'EMAIL' => '',
      'id' => '',
      'btn_txt' => 'Nothing to do here',
      'btn_state' => 'disabled'
    ];

    $selected = [
      0 => '',
      1 => '',
      2 => '',
      3 => 'selected'
    ];

    return view('admin.editstaff', [
      'blist' => $blist, 'role' => $myrole,
      'staffdata' => $staff, 'selected' => $selected, 'cben' => ''
    ]);
  }

  public function findStaff(Request $req){
    // $myrole = $req->user()->role;
    $myrole = Session::get('staffdata')['role'];
    $blist = building::all();
    $selected = [
      0 => '',
      1 => '',
      2 => '',
      3 => ''
    ];

    $staffft = User::where('staff_no', $req->staff_no)->first();
    $flooren = 'disabled';

    if($myrole == 0){
      $flooren = '';
    }

    if($staffft){
      $btntxt = 'Update';
      $btnstate = '';
      if($myrole >= $staffft->role){
        $btntxt = 'This user has >= role';
        $btnstate = 'disabled';
      }

      $staff = [
        'STAFF_NO' => $staffft->staff_no,
        'NAME' => $staffft->name,
        'UNIT' => $staffft->unit,
        'SUBUNIT' => $staffft->subunit,
        'DEPARTMENT' => $staffft->lob,
        'MOBILE_NO' => $staffft->mobile_no,
        'EMAIL' => $staffft->email,
        'id' => $staffft->id,
        'btn_txt' => $btntxt,
        'btn_state' => $btnstate
      ];

      // set which building to be checked
      $mybuildlist = json_decode($staffft->allowed_building);

        foreach($blist as $build){
          $build['chk'] = '';
          if(isset($mybuildlist)){
            foreach($mybuildlist as $myb){
              if($build->id == $myb){
                $build['chk'] = 'checked';
                break 1;
              }
            }
          }
        }


      // set the selected role
      if(isset($staffft->role)){
        $selected[$staffft->role] = 'selected';
      } else {
        $selected[3] = 'selected';
      }

    } else {
      // default buildings as unchecked
      foreach($blist as $build){
        $build['chk'] = '';
      }

      // default the selected Role
      $selected[3] = 'selected';

      // staff not exist. pull necessary data from ldap
      $lhelp = new LdapHelper;
      $lresp = $lhelp->fetchUser($req->staff_no);
      if($lresp['code'] == 404){

        $staff = [
          'STAFF_NO' => $req->staff_no,
          'NAME' => '',
          'UNIT' => '',
          'SUBUNIT' => '',
          'DEPARTMENT' => '',
          'MOBILE_NO' => '',
          'EMAIL' => '',
          'id' => '',
          'btn_txt' => 'Staff 404',
          'btn_state' => 'disabled'
        ];

        return view('admin.editstaff', [
          'blist' => $blist, 'role' => $myrole,
          'staffdata' => $staff, 'selected' => $selected,
          'alert' => 'Staff not exist in LDAP',
          'cben' => $flooren
        ]);
      } else {
          $staff = $lresp['data'];
          // append the extra info
          $staff['id'] = '';
          $staff['btn_txt'] = 'Create Profile';
          $staff['btn_state'] = '';
      }
    }
    //
    // return [
    //   'blist' => $blist, 'role' => $myrole,
    //   'staffdata' => $staff, 'selected' => $selected];

    if($req->filled('nc')){
      return view('admin.editstaff', [
        'blist' => $blist, 'role' => $myrole,
        'staffdata' => $staff, 'selected' => $selected,
        'alert' => $req->nc, 'cben' => $flooren
      ]);
    } else {
      return view('admin.editstaff', [
        'blist' => $blist, 'role' => $myrole,
        'staffdata' => $staff, 'selected' => $selected,
        'cben' => $flooren
      ]);
    }
  }

  public function assignRole(Request $req){

    $floorlist = '';
    if($req->filled('cbfloor')){
      $floorlist = json_encode($req->cbfloor);
    }

    \DB::table('users')->where('lob', $req->pporgunit)->update(array('allowed_building' => $floorlist));

    return redirect(route('admin.sr', ['alert' => 'update successful']));
  }

  // create the user profile if they never logged in yet
  public function updateUser(Request $req){

    if(isset($req->id)){
      // existing profile
      $user = User::where('id', $req->id)->first();
      $nc = 'Profile Updated';
    } else {
      $user = new User;
      $user->status = 1;
      $user->staff_no = $req->staff_no2;
      $user->name = $req->staff_name;
      $user->unit = $req->unit_disc;
      $user->subunit = $req->subunit_disc;
      $user->lob = $req->lob;
      $user->mobile_no = $req->mobile;
      $user->email = $req->email;
      $user->role = 3;
      $nc = 'Profile Created';
    }

    // to prevent 'backdoor' update
    $myrole = $req->user()->role;

    //
    if($myrole < $user->role ){
      if($req->srole >= $myrole){
        $user->role = $req->srole;
      } else {
        $nc = 'Cannot assign role higher than mine';
      }
    } else {
      $nc = 'Cannot change role of someone higher than me';
    }


    if(isset($req->cbfloor)){
      $user->allowed_building = json_encode($req->cbfloor);
    } else {
      $user->allowed_building = '';
    }

    $user->save();

    // build up the POST 'redirect' request
    $redirreq = new Request;
    $redirreq->setMethod('POST');
    $redirreq->merge(['staff_no' => $req->staff_no2, 'nc' => $nc]);

    return $this->findStaff($redirreq);

    // return redirect()->action('TAdminController@findStaff', [
    //   'staff_no' => $req->staff_no2,
    //   'nc' => $nc
    // ]);
  }

  private function doUpdateUser($staff_no, $lob, $floor_list, $srole){
    // first, check if the user exist
    $thestaff = User::where('staff_no', $staff_no)->first();

    if($thestaff){

    } else {
      // not exist. create new
      $thestaff = new User;
      $thestaff->staff_no = $staff_no;
      $thestaff->status = 1;
    }

    // then update the data
    $thestaff->role = $srole;
    // $thestaff->lob = $lob;
    $thestaff->allowed_building = $floor_list;
    $thestaff->save();

    return $thestaff;
  }

  public function deactivateUser(Request $req){

  }

  // List of Units / sub-units under current department
  public function showLov(Request $req){

    $allowedunits = Unit::where('allowed', true)->get();

    $blockedunits = Unit::where('allowed', false)->get();

    if($req->filled('err')){
      return view('admin.deptlov', ['allowedunits' => $allowedunits, 'blockedunits' => $blockedunits, 'err' => $req->err]);
    }

    return view('admin.deptlov', ['allowedunits' => $allowedunits, 'blockedunits' => $blockedunits]);

  }

  public function refreshLov(){
    // delete old data first
    // $mylob = Session::get('staffdata')['lob'];
    $mylob = 3000;
    // Unit::where('lob', $mylob)->delete();
    // SubUnit::where('lob', $mylob)->delete();

    // then look for new data
    $lhelper = new LdapHelper;
    $lresp = $lhelper->fetchSubUnits($mylob);
    // return $lresp;

    $unit = [];
    if($lresp['code'] == 200){
      foreach($lresp['data'] as $asubu){

          if(in_array($asubu['pporgunit'], $unit)){
            // dont create created unit
          } else {
            // create the units
            $nuunit = Unit::where('pporgunit', $asubu['pporgunit'])->first();
            if($nuunit){
            } else {
              $nuunit = new Unit;
              $nuunit->pporgunit = $asubu['pporgunit'];
            }

            $nuunit->lob = $mylob;
            $nuunit->pporgunitdesc = $asubu['pporgunitdesc'];
            $nuunit->save();

            array_push($unit, $asubu['pporgunit']);
          }

          // create the sub ubit
          $subunit = SubUnit::where('ppsuborg', $asubu['ppsuborg'])->first();
          if($subunit){
          } else {
            $subunit = new SubUnit;
            $subunit->ppsuborg = $asubu['ppsuborg'];
          }

          $subunit->lob = $mylob;
          $subunit->pporgunit = $asubu['pporgunit'];
          $subunit->pporgunitdesc = $asubu['pporgunitdesc'];
          $subunit->ppsuborgunitdesc = $asubu['ppsuborgunitdesc'];
          $subunit->save();
      }

      return redirect(route('admin.lov', ['err' => 'Data loaded'], false));

    } else {
        return redirect(route('admin.lov', ['err' => $lresp['msg']], false));
    }
  }

  public function allowdiv($divid){
    $div = Unit::find($divid);

    if($div){
      $div->allowed = true;
      $div->save();
      $msg = $div->pporgunitdesc . " allowed";
    } else {
      $msg = "id not found";
    }

    return redirect(route('admin.lov', ['err' => $msg], false));

  }

  public function blockdiv($divid){
    $div = Unit::find($divid);

    if($div){
      $div->allowed = false;
      $div->save();
      $msg = $div->pporgunitdesc . " blocked";
    } else {
      $msg = "id not found";
    }

    return redirect(route('admin.lov', ['err' => $msg], false));
  }

  public function updateStaffDiv(){
    $users = User::where('isvendor', 0)->get();

    foreach ($users as $key => $value) {
      UserRegisterHandler::updateUserDiv($value);
    }

    return redirect(route('admin.lov', ['err' => 'Staff DIV updated'], false));

  }

  // -------- building mgmt ----
  public function buildingIndex(Request $req){
    $offices = Office::all();
    $buildlist = building::all();

    // then call the view
    return view('admin.place', ['buildlist' => $buildlist, 'office' => $offices, 'role' => $req->user()->role]);
  }

  public function addBuilding(Request $req){
    // dd(Session::get('staffdata'));
    $build = new building;
    $build->office_id = $req->office_id;
    $build->building_name = $build->office->building_name;
    $build->floor_name = $req->floor_name;
    $build->unit = $req->unit;
    $build->created_by = Session::get('staffdata')['name'];
    $build->status = 1;

    if($req->filled('remark')){
      $build->remark = $req->remark;
    }

    $build->save();

    $buildlist = building::all();
    $offices = Office::all();

    // then call the view
    return view('admin.place', ['buildlist' => $buildlist, 'office' => $offices, 'role' => $req->user()->role]);
  }

  public function delBuilding(Request $req){
    $build = building::find($req->build_id);
    if($build){
      // get all seats under this building
      $seats = place::where('building_id', $req->build_id)->get();

      foreach($seats as $aseat){
        // get all checkins under this seat
        $cekins = $aseat->Checkin;
        foreach($cekins as $acek){
          $bh->checkOut($acek->user_id, 'building deleted');
        }

        // then delete this seat
        $aseat->delete();
      }
      $build->delete();
    }

    $buildlist = building::all();
    $offices = Office::all();
    // then call the view
    return view('admin.place', ['buildlist' => $buildlist, 'office' => $offices, 'role' => $req->user()->role]);

  }

  public function modBuilding(Request $req){
    $build = building::findOrFail($req->build_id);
    $build->office_id = $req->office_id;
    $build->building_name = $build->office->building_name;
    $build->floor_name = $req->floor_name;
    $build->unit = $req->unit;
    $build->remark = $req->remark;
    $build->save();
    return redirect(route('admin.buildetail', ['build_id' => $req->build_id], false));
  }

  public function genseats(Request $req){
    $build = building::find($req->build_id);

    for ($i=$build->seat_count + 1; $i <= $build->seat_count + $req->add_count; $i++) {
      $pcount = str_pad($i, 3, '0', STR_PAD_LEFT);
      $label = $req->label_pref . $pcount . $req->label_suf;
      $qrc = $req->qr_pref . $pcount . $req->qr_suf;

      // create the seat
      $seat = new place;
      $seat->building_id = $req->build_id;
      $seat->status = 1;
      $seat->seat_type = 1;
      $seat->priviledge = 1;
      $seat->label = $label;
      $seat->qr_code = $qrc;

      $seat->save();
    }

    // update the building seat_count
    $build = building::find($req->build_id);
    $build->seat_count = $build->seat_count + $req->add_count;
    $build->save();

    return redirect(route('admin.buildetail', ['build_id' => $build->id], false));
  }

  public function getqr(Request $req){

    $seat = place::findOrFail($req->seat_id);

    return view('admin.genqr', [
      'qrcontent' => $seat->qr_code,
      'qrlabel' => $seat->label
    ]);
  }

  public function delaseat(Request $req){
    $bh = new BookingHelper;
    $seat = place::findOrFail($req->seat_id);
    $buildid = $seat->building_id;

    // first, check if there is anyone checked in to this seat
    if($seat->status > 1){
      // force checkout that staff
      $bh->checkOut($seat->checkin_staff_id, 'seat deleted');
    }

    // then delete this seat
    $seat->delete();

    return redirect(route('admin.buildetail', ['build_id' => $buildid], false));
  }

  public function delallseat(Request $req){
    $bh = new BookingHelper;

    // get all seats under this building
    $seats = place::where('building_id', $req->build_id)->get();

    foreach($seats as $aseat){
      // first, check if there is anyone checked in to this seat
      if($aseat->status > 1){
        // force checkout that staff
        $bh->checkOut($aseat->checkin_staff_id, 'seat deleted');
      }

      // then delete this seat
      $aseat->delete();
    }

    // update the building seat_count
    $build = building::find($req->build_id);
    $build->seat_count = 0;
    $build->save();

    return redirect(route('admin.buildetail', ['build_id' => $req->build_id], false));
  }

  public function buildetail(Request $req){
    // get the building info
    $build = building::findOrFail($req->build_id);
    $offices = Office::all();

    // mark selected office
    foreach($offices as $off){
      $off->selected = '';
      if($off->id == $build->office_id){
        $off->selected = 'selected';
      }
    }

    // also get all current seats
    $seats = $build->place;

    $lov = [
      '0' => 'Inactive',
      '1' => 'Vacant',
      '2' => 'Reserved',
      '3' => 'Occupied'
    ];

    $canedit = true;
    if($req->user()->role != 0){
      $canedit = false;
      // check if this floor admin allowed to edit this floor
      $myfloorlist = json_decode($req->user()->allowed_building);
      if(isset($myfloorlist)){
        foreach($myfloorlist as $alfoor){
          if($alfoor == $req->build_id){
            $canedit = true;
            break;
          }
        }
      }
    }

    return view('admin.placedetail', [
      'build' => $build,
      'seatlist' => $seats,
      'status' => $lov,
      'office' => $offices,
      'canedit' => $canedit
    ]);
  }

  public function listAdmin(){
    $admi = User::where('status', 1)->where('role', '<', 2)->get();
    return view('admin.adminlist', ['users' => $admi]);
  }

  public function getallqr(Request $req){

    $build = building::findOrFail($req->build_id);
    $seats = place::where('building_id', $req->build_id)->get();

    $width = 300;

    if($req->filled('width')){
        $width = $req->width;
    }

    return view('admin.genallqr', [
      'location' => $build->floor_name . '@' . $build->building_name,
      'build_id' => $build->id,
      'seats' => $seats,
      'width' => $width
    ]);

  }

  public function genQR(Request $req){
    $qrcontent = $req->filled('qrc') ? $req->qrc : "empty";
    $qrlabel = $req->filled('qrl') ? $req->qrl : "empty";

    return view('admin.genqr', [
      'qrcontent' => $qrcontent,
      'qrlabel' => $qrlabel
    ]);

  }

  public function reglist(Request $req){
    // set the default view
    $type = User::where('status', 2)->where('verified', true)->count() == 0 ? 'active' : 'pending';
    $title = 'Active Vendor Users';
    // overwrite with choice if any
    if($req->filled('type')){
      $type = $req->type;
    }

    // then search based on type
    if($type == 'pending'){
      $users = User::where('status', 2)
        ->where('isvendor', 1)
        ->where('verified', true)
        ->get();
      $title = 'Vendor Users that requires approval';
    } elseif($type == 'email'){
      $users = User::where('status', 2)
        ->where('isvendor', 1)
        ->where('verified', false)
        ->get();
      $title = 'Vendor registration with pending email verification';
    } else {
      $users = User::where('status', 1)
        ->where('isvendor', 1)
        ->where('verified', true)
        ->get();
    }

    if($req->filled('alert')){
      return view('admin.pendingreg', ['users' => $users, 'alert' => $req->alert,
        'type' => $type, 'title' => $title
      ]);
    }

    return view('admin.pendingreg', ['users' => $users, 'type' => $type, 'title' => $title]);

  }

  public function regapprove(Request $req){
    $user = User::findOrFail($req->staff_id);
    $user->status = 1;
    $user->save();

    \Mail::to($user->email)->send(new RegApproved($user));

    return redirect(route('admin.reglist', ['alert' => 'Approved: ' . $user->name, 'type' => 'pending'], false));
  }

  public function regreject(Request $req){
    $user = User::findOrFail($req->staff_id);

    if($user->verifyUser){
      $user->verifyUser->delete();
    }

    // reduce the user count
    $pner = $user->Partner;
    $pner->decrement('staff_count');
    $pner->save();

    $rj = new RejectedUser;
    $rj->staff_no = $user->staff_no;
    $rj->name = $user->name;
    $rj->email = $user->email;
    $rj->mobile_no = $user->mobile_no;
    $rj->partner_id = $user->partner_id;
    $rj->remark = $req->remark;
    $rj->action = $req->act;
    $rj->rejected_by = Session::get('staffdata')['id'];
    $rj->save();

    // remove this user's checkin
    Checkin::where('user_id', $user->id)->delete();

    $user->delete();

    \Mail::to($rj->email)->send(new RegRejected($rj));

    return redirect(route('admin.reglist', ['alert' => 'User rejected'], false));
  }

  public function delstaff(Request $req){
    $user = User::findOrFail($req->staff_id);

    if($user->verifyUser){
      $user->verifyUser->delete();
    }

    // reduce the user count
    $pner = $user->Partner;
    $pner->decrement('staff_count');
    $pner->save();

    $rj = new RejectedUser;
    $rj->staff_no = $user->staff_no;
    $rj->name = $user->name;
    $rj->email = $user->email;
    $rj->mobile_no = $user->mobile_no;
    $rj->partner_id = $user->partner_id;
    $rj->remark = $req->remark;
    $rj->action = 'deleted';
    $rj->rejected_by = Session::get('staffdata')['id'];
    $rj->save();
    $user->delete();

    \Mail::to($rj->email)->send(new RegRejected($rj));

    return redirect(route('admin.reglist', ['alert' => 'User deleted'], false));
  }

  // ===================================
  // manage meeting rooms
  public function meetroom(Request $req){

    $meetrooms = place::where('seat_type', 2)->get();

    if($req->user()->role == 0){
      $buildlist = building::all();
    } else {
      $allowed = json_decode($req->user()->allowed_building);
      if(isset($allowed)){
        $buildlist = building::whereIn('id', $allowed)->get();

        foreach ($meetrooms as $key => $value) {
          if(!in_array($value->building_id, $allowed)){
            $value->cannot_edit = true;
          }
        }
      } else {
        $buildlist = [];
        foreach ($meetrooms as $key => $value) {
          $value->cannot_edit = true;
        }
      }

    }

    if($req->filled('alert')){
      return view('admin.meetroom', ['buildings' => $buildlist, 'data' => $meetrooms, 'alert' => $req->alert]);
    }

    return view('admin.meetroom', ['buildings' => $buildlist, 'data' => $meetrooms]);

  }

  public function meetroomAdd(Request $req){
    // search for dups
    $place = place::where('label', $req->name)->where('building_id', $req->building_id)->first();
    if($place){
      return redirect(route('admin.meetroom', ['alert', 'Meeting room exists']));
    }

    $place = place::where('qr_code', $req->qrdata)->first();
    if($place){
      return redirect(route('admin.meetroom', ['alert', 'Duplicate QR data']));
    }

    // create new
    $seat = new place;
    $seat->building_id = $req->building_id;
    $seat->status = 1;
    $seat->seat_type = 2;
    $seat->priviledge = 1;
    $seat->label = $req->name;
    $seat->qr_code = $req->qrdata;
    $seat->save();
    return redirect(route('admin.meetroom', ['alert' => 'Meeting room ' . $req->name . ' added']));
  }

  public function meetroomEdit(Request $req){
    $seat = place::findOrFail($req->id);

    // search for possible dups after edit
    $place = place::where('label', $req->name)->where('building_id', $req->building_id)->where('id', '!=', $req->id)->first();
    if($place){
      return redirect(route('admin.meetroom', ['alert' => 'Error: will cause duplicate -> ' . $req->name]));
    }

    $place = place::where('qr_code', $req->qrdata)->where('id', '!=', $req->id)->first();
    if($place){
      return redirect(route('admin.meetroom', ['alert' => 'Duplicate QR data']));
    }

    $seat->label = $req->name;
    $seat->qr_code = $req->qrdata;
    $seat->building_id = $req->building_id;
    $seat->save();
    return redirect(route('admin.meetroom', ['alert' => 'Meeting room ' . $req->name . ' updated']));
  }

  public function meetroomDel(Request $req){
    $bh = new BookingHelper;
    $seat = place::findOrFail($req->id);

    // get all checkins under this meeting room
    $cekins = $seat->Checkin;
    foreach($cekins as $acek){
      $bh->checkOut($acek->user_id, 'meeting room deleted');
    }

    // then delete the seat
    $seat->delete();
    return redirect(route('admin.meetroom', ['alert'=> 'Meeting room deleted']));
  }

  public function loadji(Request $req){
    return view('admin.loadji');
  }

  public function doloadji(Request $req){

    $ifule = $req->file('infile');

    $ufile = fopen($ifule->getRealPath(), 'r');

    $header = fgetcsv($ufile, 0, ",");  // skip the header
    $divlist = [];
    $divids = [];
    $divname = [];
    $divcount = [];
    $subunitlist = [];

    while(($onelineofdata = fgetcsv($ufile, 0, ",")) !== false){
      // check if this div is loaded
      $divpos = array_search($onelineofdata[1], $divlist);
      if($divpos !== false){
        $divcount[$divpos]++;
        $divid = $divids[$divpos];
      } else {
        // new. load it

        if($onelineofdata[1] != '#N/A'){
          $divid = UserRegisterHandler::getDivision($onelineofdata[1], $onelineofdata[2]);
        } else {
          $divid = 0;
        }


        array_push($divlist, $onelineofdata[1]);
        array_push($divname, $onelineofdata[2]);
        array_push($divcount, 1);
        array_push($divids, $divid);
      }

      // also check if subunit is loaded
      $unitpos = array_search($onelineofdata[4], $subunitlist);
      if($unitpos !== false){
      } else {
        // create it
        if($onelineofdata[1] != '#N/A' && $onelineofdata[3] != '#N/A'){
          UserRegisterHandler::getUnit($onelineofdata[1], $onelineofdata[2], $onelineofdata[3], $onelineofdata[4]);
        }
        array_push($subunitlist, $onelineofdata[4]);
      }

      // update the user
      UserRegisterHandler::updateStaffInfoFromJI($onelineofdata[13], $onelineofdata[11], $onelineofdata[7], $onelineofdata[9], $onelineofdata[2], $onelineofdata[4], $divid, $onelineofdata[1]);

    }

    $outlist = [];
    foreach ($divlist as $key => $value) {
      array_push($outlist, [
        'div' => $divlist[$key] . ' - ' . $divname[$key],
        'count' => $divcount[$key]
      ]);
    }

    return view('admin.loadji', [
      'alert' => 'Data loaded',
      'loaded' => true,
      'dataasummary' => $outlist
    ]);

  }

  public function dlji(){
    return \Storage::download('public/JI_GITD__Sept_2019_v2.csv', 'JI_GITD__Sept_2019_v2.csv');
  }


}
