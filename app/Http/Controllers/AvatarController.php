<?php

namespace App\Http\Controllers;

use App\Avatar;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(Request $req){

    // dd($req->session());
    // dd($errors);

    $cfgs = Avatar::all();

    if($req->filled('alert')){
      return view('admin.avatar', [
        'alert' => $req->alert,
        'data' => $cfgs
      ]);
    } else {
      return view('admin.avatar', ['data' => $cfgs]);
    }
  }

  public function addedit(Request $req){

    // check for duplicate rank
    $dupcheck = Avatar::where('rank', $req->rank)->first();
    if($dupcheck){
      // dd($dupcheck);
      if($req->filled('id')){
        if($dupcheck->id != $req->id){
          return redirect(route('avatar.list', ['alert' => 'update failed. duplicate rank'], false));
        }
      } else {
        // dd('error');
        return redirect()->back()->withInput()->withErrors(['rank' => 'duplicate rank']);
      }
    }

    $cfg = false;
    if($req->filled('id')){
      $cfg = Avatar::find($req->id);
      $msg = 'record updated';
    } else {
      $cfg = new Avatar;
      $msg = 'record added';
    }

    $cfg->rank = $req->rank;
    $cfg->rank_name = $req->rank_name;
    $cfg->min_hours = $req->min_hours;
    $cfg->max_hours = $req->max_hours;
    $cfg->image_url = $req->image_url;
    $cfg->image_credit = $req->image_credit;
    $cfg->save();

    return redirect(route('avatar.list', ['alert' => $msg]));
  }

  public function del(Request $req){
    if($req->id == 0){
      return redirect(route('avatar.list', ['alert' => 'Cannot delete default avatar']));
    }

    Avatar::find($req->id)->delete();
    return redirect(route('avatar.list', ['alert' => 'record deleted']));
  }
}
