<?php

namespace App\Http\Controllers;

use App\Partner;
use App\User;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(Request $req){
    $partner = Partner::all();
    return view('admin.partners', ['currtasklist' => $partner]);
  }

  public function add(Request $req){
    $partner = new Partner;
    $partner->comp_name = $req->name;
    $partner->staff_count = 0;
    $partner->save();
    return redirect(route('partner.list', ['alert' => $req->name . ' added'], false));
  }

  public function del(Request $req){
    $part = Partner::findOrFail($req->id);
    $pname = $part->comp_name;
    $scount = $part->Users->count();

    // delete the staffs
    $part->Users->delete();

    // then delete the partner
    $part->delete();

    return redirect(route('partner.list', ['alert' => $pname . ' deleted along with ' . $scount . ' staffs'], false));
  }

  public function edit(Request $req){
    $part = Partner::findOrFail($req->id);
    $oldname = $part->comp_name;
    $part->comp_name = $req->name;
    $part->save();
    return redirect(route('partner.list', ['alert' => $oldname . ' changed to ' . $req->name], false));
  }



}
