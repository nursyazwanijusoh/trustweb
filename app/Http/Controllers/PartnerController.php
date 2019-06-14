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
    return redirect(route('partner.list', [], false));
  }

  public function del(Request $req){
    $part = Partner::findOrFail($req->id);

    if($part->staff_count > 0){
      User::where('partner_id', $part->id)->delete();
    }

    $part->delete();

    return redirect(route('partner.list', [], false));
  }



}
