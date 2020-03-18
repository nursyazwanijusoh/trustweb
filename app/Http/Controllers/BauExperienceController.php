<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BauExperience;

class BauExperienceController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(Request $req){
    $explist = BauExperience::all();

    return view('admin.bauexp', ['currtasklist' => $explist]);
  }

  public function add(Request $req){
    // just in case
    if(!$req->filled('name')){
      return redirect(route('bauexp.list'))->with([
        'alert' => 'missing input',
        'a_type' => 'danger'
      ]);
    }

    // check for duplicate
    $dup = BauExperience::where('name', $req->name)->first();
    if($dup){
      return redirect()->back()->withInput()->withErrors(['name' => 'Already exists']);
    }

    $nube = new BauExperience;
    $nube->name = $req->name;
    $nube->added_by = $req->user()->id;
    $nube->edited_by = $req->user()->id;
    $nube->save();

    return redirect(route('bauexp.list'))->with([
      'alert' => $nube->name . ' added',
      'a_type' => 'success'
    ]);

  }

  public function edit(Request $req){
    $old = BauExperience::find($req->id);
    if($old){

      $oname = $old->name;

      // check for possible dup with others
      $dup = BauExperience::where('name', $req->name)
        ->where('id', '!=', $req->id)->first();
      if($dup){
        return redirect(route('bauexp.list'))->with([
          'alert' => 'New value will cause duplicate',
          'a_type' => 'danger'
        ]);
      }

      // do the update
      $old->name = $req->name;
      $old->edited_by = $req->user()->id;
      $old->save();

      return redirect(route('bauexp.list'))->with([
        'alert' => $oname . ' updated to ' . $old->name,
        'a_type' => 'success'
      ]);

    } else {
      return redirect(route('bauexp.list'))->with([
        'alert' => 'Item no longer exist',
        'a_type' => 'danger'
      ]);
    }
  }

  public function del(Request $req){
    $old = BauExperience::find($req->id);
    if($old){
      $old->edited_by = $req->user()->id;
      $old->save();
      $old->delete();

      return redirect(route('bauexp.list'))->with([
        'alert' => $old->name . ' deleted',
        'a_type' => 'success'
      ]);
    } else {
      return redirect(route('bauexp.list'))->with([
        'alert' => 'Item no longer exist',
        'a_type' => 'danger'
      ]);
    }
  }

  public function staffWithExp(Request $req){
    // just in case
    if(!$req->filled('id')){
      return redirect(route('bauexp.list'));
    }

    $old = BauExperience::find($req->id);
    if($old){
      return view('admin.bauexpstafflist', [
        'be' => $old
      ]);
    } else {
      return redirect(route('bauexp.list'))->with([
        'alert' => 'Item no longer exist',
        'a_type' => 'danger'
      ]);
    }
  }
}
