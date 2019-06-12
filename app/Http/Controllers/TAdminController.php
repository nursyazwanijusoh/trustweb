<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\AdminGate;
use App\TaskCategory;
use App\ActivityType;
use App\building;
use App\place;
use App\User;
use App\Unit;
use App\SubUnit;
use App\Feedback;
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

    return view('admin.index', ['fbc' => $fbcount]);
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
      'staffdata' => $staff, 'selected' => $selected
    ]);
  }

  public function findStaff(Request $req){
    $myrole = Session::get('staffdata')['role'];
    $blist = building::all();
    $selected = [
      0 => '',
      1 => '',
      2 => '',
      3 => ''
    ];

    $staffft = User::where('staff_no', $req->staff_no)->first();

    if($staffft){
      $staff = [
        'STAFF_NO' => $staffft->staff_no,
        'NAME' => $staffft->name,
        'UNIT' => $staffft->unit,
        'SUBUNIT' => $staffft->subunit,
        'DEPARTMENT' => $staffft->lob,
        'MOBILE_NO' => $staffft->mobile_no,
        'EMAIL' => $staffft->email,
        'id' => $staffft->id,
        'btn_txt' => 'Update',
        'btn_state' => ''
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
          'alert' => 'Staff not exist in LDAP'
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
        'alert' => $req->nc
      ]);
    } else {
      return view('admin.editstaff', [
        'blist' => $blist, 'role' => $myrole,
        'staffdata' => $staff, 'selected' => $selected
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
      $nc = 'Profile Created';
    }

    $user->role = $req->srole;
    if(isset($req->cbfloor)){
      $user->allowed_building = json_encode($req->cbfloor);
    } else {
      $user->allowed_building = '';
    }

    // dd($user);

    $user->save();

    // build up the POST 'redirect' request
    $redirreq = new Request;
    $redirreq->setMethod('POST');
    $redirreq->merge(['staff_no' => $req->staff_no2]);

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

  // List of Units / sub-units under current department
  public function showLov(Request $req){
    // $mylob = Session::get('staffdata')['lob'];
    $mylob = 3000;

    $unitlist = Unit::where('lob', $mylob)->get();
    foreach($unitlist as $aunit){
      $subunitlist = SubUnit::where('lob', $mylob)->where('pporgunit', $aunit->pporgunit)->get();
      $aunit['subunit'] = $subunitlist;
    }

    if($req->filled('err')){
      return view('admin.deptlov', ['deptid' => $mylob, 'units' => $unitlist, 'err' => $req->err]);
    }

    return view('admin.deptlov', ['deptid' => $mylob, 'units' => $unitlist]);

  }

  public function refreshLov(){
    // delete old data first
    // $mylob = Session::get('staffdata')['lob'];
    $mylob = 3000;
    Unit::where('lob', $mylob)->delete();
    SubUnit::where('lob', $mylob)->delete();

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
            $nuunit = new Unit;
            $nuunit->lob = $mylob;
            $nuunit->pporgunit = $asubu['pporgunit'];
            $nuunit->pporgunitdesc = $asubu['pporgunitdesc'];
            $nuunit->save();

            array_push($unit, $asubu['pporgunit']);
          }

          // create the sub ubit
          $subunit = new SubUnit;
          $subunit->lob = $mylob;
          $subunit->pporgunit = $asubu['pporgunit'];
          $subunit->pporgunitdesc = $asubu['pporgunitdesc'];
          $subunit->ppsuborg = $asubu['ppsuborg'];
          $subunit->ppsuborgunitdesc = $asubu['ppsuborgunitdesc'];
          $subunit->save();


      }

      return redirect(route('admin.lov', ['err' => 'Data loaded'], false));

    } else {
        return redirect(route('admin.lov', ['err' => $lresp['msg']], false));
    }
  }

  // -------- building mgmt ----
  public function buildingIndex(){
    // fetch all current list of buildings
    $buildlist = building::all();

    // then call the view
    return view('admin.place', ['buildlist' => $buildlist]);
  }



  public function addBuilding(Request $req){
    $build = new building;
    $build->building_name = $req->building_name;
    $build->floor_name = $req->floor_name;
    $build->unit = $req->unit;
    $build->created_by = Session::get('staffdata')['name'];
    $build->status = 1;

    if($req->filled('remark')){
      $build->remark = $req->remark;
    }

    $build->save();

    $buildlist = building::all();

    // then call the view
    return view('admin.place', ['buildlist' => $buildlist]);
  }

  public function delBuilding(Request $req){
    $build = building::find($req->build_id);
    if($build){
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
      $build->delete();
    }

    $buildlist = building::all();
    // then call the view
    return view('admin.place', ['buildlist' => $buildlist]);

  }

  public function modBuilding(Request $req){
    $build = building::findOrFail($req->build_id);
    $build->building_name = $req->building_name;
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

    // also get all current seats
    $seats = place::where('building_id', $req->build_id)->get();

    $lov = [
      '0' => 'Inactive',
      '1' => 'Vacant',
      '2' => 'Reserved',
      '3' => 'Occupied'
    ];

    return view('admin.placedetail', [
      'build' => $build,
      'seatlist' => $seats,
      'status' => $lov
    ]);
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

  public function showSharedSkillset(Request $req){

    return view('admin.sharedskillset', []);
  }

  public function deleteSharedSkillset(Request $req){

    $currskilset = [];

    return view('admin.sharedskillset', ['alert' => 'Skillset Deleted', 'currtasklist' => $currskilset]);
  }

  public function addSharedSkillset(Request $req){

    $currskilset = [];

    return view('admin.sharedskillset', ['alert' => 'Skillset Added', 'currtasklist' => $currskilset]);
  }

}
