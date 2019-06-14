<?php

namespace App\Http\Controllers;

use App\CommonConfig;
use Illuminate\Http\Request;

class CommonConfigController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(Request $req){

    $cfgs = CommonConfig::all();

    if($req->filled('alert')){
      return view('admin.commonconfig', [
        'alert' => $req->alert,
        'data' => $cfgs
      ]);
    } else {
      return view('admin.commonconfig', ['data' => $cfgs]);
    }
  }

  public function addedit(Request $req){
    $cfg = CommonConfig::where('key', $req->key)->first();
    $msg = 'record updated';
    if(!$cfg){
      $cfg = new CommonConfig;
      $cfg->key = $req->key;
      $msg = 'record added';
    }
    $cfg->value = $req->value;
    $cfg->save();

    return redirect(route('cfg.list', ['alert' => 'record updated']));
  }

  public function edit(Request $req){
    $cfg = CommonConfig::findOrFail($req->id);
    $cfg->key = $req->key;
    $cfg->value = $req->value;
    $cfg->save();

    return redirect(route('cfg.list', ['alert' => 'record updated']));
  }

  public function del(Request $req){
    CommonConfig::find($req->id)->delete();
    return redirect(route('cfg.list', ['alert' => 'record deleted']));
  }


}
