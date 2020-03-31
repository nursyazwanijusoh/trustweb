<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobscope;

class JobscopeController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  // display the add form and list existing objects
  public function list(Request $req){
    return view('admin.jobscope', [
      'data' => Jobscope::all()
    ]);
  }

  public function add(Request $req){
    // check for existing object
    $dup = Jobscope::where('name', $req->name)->first();
    if($dup){
      return redirect()->back()->with([
        'a_type' => 'danger',
        'alert' => 'Same name already exist'
      ]);
    }

    $neu = new Jobscope;
    $neu->name = $req->name;
    $neu->hint = $req->hint;
    $neu->save();

    return redirect(route('bauexp.role.list'))->with([
      'a_type' => 'success',
      'alert' => $neu->name . ' added'
    ]);
  }

  public function edit(Request $req){
    // check for duplicate
    $dup = Jobscope::where('name', $req->name)
      ->where('id', '!=', $req->id)->first();

    if($dup){
      return redirect()->back()->with([
        'a_type' => 'danger',
        'alert' => 'Same name already exist'
      ]);
    }

    // find the existing role
    $neu = Jobscope::find($req->id);
    if($neu){
      // update the role
      $neu->name = $req->name;
      $neu->hint = $req->hint;
      $neu->save();

      return redirect(route('bauexp.role.list'))->with([
        'a_type' => 'success',
        'alert' => $neu->name . ' updated'
      ]);
    } else {
      // existing role 404
      return redirect()->back()->with([
        'a_type' => 'warning',
        'alert' => 'Existing role not found'
      ]);
    }


  }

  public function del(Request $req){
    $neu = Jobscope::find($req->id);
    if($neu){
      $neu->delete();
      return redirect(route('bauexp.role.list'))->with([
        'a_type' => 'info',
        'alert' => $neu->name . ' deleted'
      ]);
    } else {
      // existing role 404
      return redirect()->back()->with([
        'a_type' => 'warning',
        'alert' => 'Selected role not found'
      ]);
    }
  }
}
