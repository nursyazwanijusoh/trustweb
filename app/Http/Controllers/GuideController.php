<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Guide;

class GuideController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(){
    $gl = Guide::all();

    return view('admin.guide', [
      'guides' => $gl
    ]);

  }

  public function add(Request $req){
    $g = new Guide;
    $g->title = $req->title;
    $g->url = $req->url;
    $g->desc = $req->desc;
    $g->added_by = $req->user()->id;
    $g->save();

    return redirect(route('admin.guides'));
  }

  public function delete(Request $req){
    $g = Guide::find($req->gid);
    if($g){
      $g->delete();
    }

    return redirect(route('admin.guides'));
  }

}
