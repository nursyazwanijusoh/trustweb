<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AgileResourceTeam;

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

    } else {
      return redirect(route('art.list'));
    }
  }

  public function del(Request $req){

  }
}
