<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AgileResourceTeam;
use App\User;

class AgileResourceTeamController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(Request $req){

    $arts = AgileResourceTeam::where('status', 'Active')->get();

    return view('admin.artmgmt', [
      'data' => $arts
    ]);
  }

  public function add(Request $req){
    if($req->filled('repno')){

      $user = User::where('staff_no', $req->repno)->first();

      if($user){
        // check for duplicates
        $dup = AgileResourceTeam::where('status', 'Active')
          ->where('user_id', $user->id)->first();

        if($dup){
          // already an active member
          return back()->withInput()->with([
            'a_type' => 'warning',
            'alert' => 'User is already an active ART member'
          ])->withErrors([
            'repno' => 'Duplicate'
          ]);
        } else {
          // add new
          $art = new AgileResourceTeam;
          $art->user_id = $user->id;
          $art->added_by = $req->user()->id;
          $art->save();

          return redirect(route('art.list', [], false))->with([
            'a_type' => 'success',
            'alert' => 'ART member added'
          ]);
        }
      } else {
        // user 404
        return back()->withInput()->with([
          'a_type' => 'warning',
          'alert' => 'Invalid input'
        ])->withErrors([
          'repno' => 'User not exist'
        ]);
      }



    } else {
      return redirect(route('art.list'));
    }
  }

  public function del(Request $req){

    $art = AgileResourceTeam::find($req->artid);
    if($art){
      if($art->status == 'Active'){
        $art->status = 'Removed';
        $art->deleted_by = $req->user()->id;
        $art->save();

        return redirect(route('art.list', [], false))->with([
          'a_type' => 'success',
          'alert' => 'Removed ' . $art->User->name . ' from ART'
        ]);
      }
    } else {
      return back()->with([
        'a_type' => 'warning',
        'alert' => 'Failed to remove. Entry 404'
      ]);
    }


  }
}
