<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\common\IopHandler;
use App\User;
use App\StaffPersMap;
use App\SapEmpProfile;
use App\SapLeaveInfo;
use App\common\BatchHelper;

class SapLoadController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function showSummaryPage(Request $req){
    $emp = SapEmpProfile::where('load_status', 'N')->count();
    $cuti = SapLeaveInfo::where('load_status', 'N')->count();
    $nullpersno = User::whereNull('persno')->count();

    return view('admin.sapdash', [
      'eplist' => $emp,
      'nullpersno' => $nullpersno,
      'cuticount' => $cuti
    ]);
  }

  public function mapPersNo(Request $req){
    $userlist = User::whereNull('persno')->get();

    foreach($userlist as $u){
      $spm = StaffPersMap::where('staff_no', $u->staff_no)->first();
      if($spm){
        $u->persno = $spm->persno;
        $u->save();
      }
    }

    return User::whereNull('persno')->count();
  }

  public function processOM(Request $req){
    $dat = SapEmpProfile::find($req->id);
    if($dat){
      if($dat->load_status != 'N'){
        return "already loaded with status " . $dat->load_status;
      }

      // check if the persno is already there in users
      $user = User::where('persno', $dat->persno)->first();
      if($user){

      } else {
        // no user with this
      }

    }
  }

  public function loadDataCuti(Request $req){
    set_time_limit(0);
    BatchHelper::loadCutiData($req->user()->id);

  }
}
