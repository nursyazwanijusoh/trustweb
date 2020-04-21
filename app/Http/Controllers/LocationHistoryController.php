<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\common\UserRegisterHandler;
use App\User;

class LocationHistoryController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function list(Request $req){

    $staff_id = $req->user()->id;
    $isvisitor = false;
    if($req->filled('staff_id')){
      if($req->staff_id != $staff_id){
        $isvisitor = true;
      }

      $staff_id = $req->staff_id;
    }

    $user = User::find($staff_id);
    if($user){
      $katmanas = UserRegisterHandler::attLocHistory($staff_id);

      return view('staff.clockhist', [
        'isvisitor' => $isvisitor,
        'user' => $user,
        'lochist' => $katmanas
      ]);
    } else {
      return redirect(route('staff'));
    }
  }

  public function clockin(Request $req){
    if($req->filled('lat') && $req->filled('long')){
      if($req->filled('action')){
        if($req->action == 'updateloc'){
          UserRegisterHandler::attUpdateLoc($req->staff_id,
            $req->lat, $req->long,
            $req->filled('reason') ? $req->reason : '',
            $req->address
          );
        } elseif ($req->action == 'clockout') {
          UserRegisterHandler::attClockOut($req->staff_id, \Carbon\Carbon::now(),
            $req->lat, $req->long,
            $req->filled('reason') ? $req->reason : '',
            $req->address
          );
        } elseif ($req->action == 'clockin') {
          UserRegisterHandler::attClockIn($req);
        } else {
          return redirect()->back()->with([
            'a_type' => 'danger',
            'alert' => 'Unknown action code'
          ]);
        }

        return redirect(route('clock.list'));

      } else {
        return redirect()->back()->with([
          'a_type' => 'danger',
          'alert' => 'No action code'
        ]);
      }
    } else {
      return redirect()->back()->with([
        'a_type' => 'danger',
        'alert' => 'No coordinate provided'
      ]);
    }
  }

}
