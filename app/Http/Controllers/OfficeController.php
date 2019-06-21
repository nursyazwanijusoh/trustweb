<?php

namespace App\Http\Controllers;

use App\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(Request $req){

    $offs = Office::all();

    if($req->filled('alert')){
      return view('admin.geoffice', ['data' => $offs, 'alert' => $req->alert]);
    }

    return view('admin.geoffice', ['data' => $offs]);
  }

  public function addedit(Request $req){
    $c_staff_id = \Session::get('staffdata')['id'];
    $off = Office::where('building_name', $req->building_name)->first();
    $msg = 'record updated';
    if(!$off){
      $off = new Office;
      $off->building_name = $req->building_name;
      $off->created_by = $c_staff_id;
      $msg = 'record added';
    }

    $off->modified_by = $c_staff_id;
    $off->a_latitude = $req->a_latitude;
    $off->a_longitude = $req->a_longitude;
    $off->b_latitude = $req->b_latitude;
    $off->b_longitude = $req->b_longitude;
    $off->save();

    return redirect(route('geo.list', ['alert' => 'record updated']));
  }

  public function edit(Request $req){
    $off = Office::findOrFail($req->id);
    $off->modified_by = \Session::get('staffdata')['id'];
    $off->building_name = $req->building_name;
    $off->a_latitude = $req->a_latitude;
    $off->a_longitude = $req->a_longitude;
    $off->b_latitude = $req->b_latitude;
    $off->b_longitude = $req->b_longitude;

    $off->save();

    return redirect(route('geo.list', ['alert' => 'record updated']));
  }

  public function del(Request $req){
    Office::find($req->id)->delete();
    return redirect(route('geo.list', ['alert' => 'record deleted']));
  }
}
