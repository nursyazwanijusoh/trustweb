<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskCategory;
use App\building;
use App\User;
use Session;
use App\Api\V1\Controllers\LdapHelper;

class TAdminController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  // main admin page
  public function index(){
    return view('admin.index');
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


  // ------- ROLES --------
  public function showStaffRole(Request $req){
    // get the list of buildings
    $blist = building::all();
    $myrole = Session::get('staffdata')['role'];

    if($req->filled('alert')){
      return view('admin.assignrole', [
        'blist' => $blist, 'role' => $myrole,
        'alert' => $req->alert . ' records updated'
      ]);
    } else {
      return view('admin.assignrole', ['blist' => $blist, 'role' => $myrole]);
    }
  }

  public function blankStaff(){
    $blist = building::all();
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

        foreach($mybuildlist as $myb){
          if($build->id == $myb){
            $build['chk'] = 'checked';
            break 1;
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
    $text = str_replace("\r\n", "\n", trim($req->staffs));
    $stafflist = explode("\n", $text);
    $floorlist = '';
    if($req->filled('cbfloor')){
      $floorlist = json_encode($req->cbfloor);
    }
    $counter = 0;

    foreach($stafflist as $astaff){
      $dump = $this->doUpdateUser($astaff, '', $floorlist, $req->srole);
      $counter++;
    }


    return redirect(route('admin.sr', ['alert' => $counter]));
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
      $user->lob = $req->lob;
      $user->mobile_no = $req->mobile;
      $user->email = $req->email;
      $nc = 'Profile Created';
    }

    $user->role = $req->srole;
    if(isset($req->cbfloor)){
      $user->allowed_building = json_encode($req->cbfloor);
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

  // -------- building mgmt ----
  public function buildingIndex(){
    // fetch all current list of buildings
    $buildlist = building::all();

    // then call the view
    return view('admin.place', ['buildlist' => $buildlist]);
  }

  public function addBuilding(Request $req){

  }

  public function delBuilding(Request $req){

  }

}
