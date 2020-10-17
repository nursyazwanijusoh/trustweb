<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CompGroup;
use App\Unit;
use App\User;

class CompGroupController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  // list all groups
  public function list(){
    return view('admin.cgrp',
      ['data' => CompGroup::all()]
    );
  }

  // add new group
  public function add(Request $req){
    // check for dup name
    $dup = CompGroup::where('name', $req->code)->first();
    if($dup){
      return redirect()->back()->withInput()->withErrors(['code' => 'Already exists']);
    }

    $grp = new CompGroup;
    $grp->name = $req->code;
    $grp->created_by = $req->user()->id;
    $grp->save();

    return redirect(route('cgrp.view', ['id' => $grp->id], false));

  }

  // edit group name
  public function edit(Request $req){
    // check for dup name
    $dup = CompGroup::where('name', $req->code);
    if($dup){
      return redirect()->back()->withInput()->withErrors(['code' => 'Already exists']);
    }

    $grp = CompGroup::find($req->id);
    if($grp){
      $grp->name = $req->code;
      $grp->save();
      return redirect(route('cgrp.view', ['id' => $grp->id], false))->with(['alert' => 'Group name updated']);
    } else {
      return redirect(route('cgrp.list', [], false))->with(['alert' => 'Group not found']);
    }

  }

  // delete group
  public function del(Request $req){
    $grp = CompGroup::find($req->id);
    if($grp){

      // unassign divs
      if($grp->Members->count() > 0){
        Unit::whereIn('id', $grp->Members->pluck('id'))->update(['comp_group_id' => null]);
      }

      $grp->deleted_by = $req->user()->id;
      $grp->save();
      $grp->delete();
      return redirect(route('cgrp.list', [], false))->with(['alert' => 'Group deleted']);
    } else {
      return redirect(route('cgrp.list', [], false))->with(['alert' => 'Group not found']);
    }
  }

  // view group members
  public function view(Request $req){
    $cgrp = CompGroup::find($req->id);

    if($cgrp){
      $freeunits = Unit::whereNull('comp_group_id')->get();
      $otherunit = Unit::whereNotNull('comp_group_id')
        ->where('comp_group_id', '!=', $cgrp->id)->get();

      return view('admin.cgrpdetail', [
        'cgrp' => $cgrp,
        'freeg' => $freeunits,
        'otherg' => $otherunit
      ]);
    } else {
      return redirect(route('cgrp.list', [], false))->with(['alert' => 'Selected group not found']);
    }
  }

  // assign a div to this group
  public function take(Request $req){
    $cunit = Unit::find($req->id);
    if($cunit){
      $cunit->comp_group_id = $req->gid;
      $cunit->save();
      return redirect(route('cgrp.view', ['id' => $req->gid], false))->with(['alert' => 'Unit added to group']);
    } else {
      return redirect(route('cgrp.view', ['id' => $req->gid], false))->with(['alert' => 'Unit not found']);
    }
  }

  // remove a div from this group
  public function remove(Request $req){
    $cunit = Unit::find($req->id);
    if($cunit){

      // double check who this unit belongs to
      if($cunit->comp_group_id == $req->gid){
        $cunit->comp_group_id = null;
        $cunit->save();
        return redirect(route('cgrp.view', ['id' => $req->gid], false))->with(['alert' => 'Unit removed']);
      } else {
        return redirect(route('cgrp.view', ['id' => $req->gid], false))->with(['alert' => 'Unit belongs to other group']);
      }

    } else {
      return redirect(route('cgrp.view', ['id' => $req->gid], false))->with(['alert' => 'Unit not found']);
    }
  }

  public function removerep(Request $req){
    $cgrp = CompGroup::find($req->gid);

    if($cgrp){
      $cgrp->Users()->detach($req->uid);

      return redirect(route('cgrp.view', ['id' => $req->gid], false))->with(['alert' => 'Rep removed']);
    } else {
      return redirect(route('cgrp.list', [], false))->with(['alert' => 'Selected group not found']);
    }
  }

  public function addrep(Request $req){
    $cgrp = CompGroup::find($req->gid);

    if($cgrp){
      $user = User::where('staff_no', $req->repno)->first();
      if($user){

        // $orlist = $cgrp->Users()->pluck('id');
        // array_push($orlist, )
        //
        // dd();

        $cgrp->Users()->attach($user->id);
      } else {
        return back()->withInput()->withErrors([
          'repno' => 'User not found'
        ]);
      }

      return redirect(route('cgrp.view', ['id' => $req->gid], false))->with(['alert' => 'Rep added']);
    } else {
      return redirect(route('cgrp.list', [], false))->with(['alert' => 'Selected group not found']);
    }
  }

}
