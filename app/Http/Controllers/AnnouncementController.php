<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Announcement;


class AnnouncementController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('AdminGate');
  }

  public function list(Request $req){
    $today = date('Y-m-d');
    $lastmon = new Carbon;
    $lastmon->subMonth();
    $annlist = Announcement::whereDate('end_date', '>=', $lastmon)->get();

    return view('admin.announcements', [
      'today' => $today,
      'list' => $annlist
    ]);
  }

  public function add(Request $req){
    $an = new Announcement;
    $an->content = $req->content;
    $an->start_date = $req->startdate;
    $an->end_date = $req->enddate;
    $an->added_by = $req->user()->id;

    if($req->filled('linktext')){
      $an->url_text = $req->linktext;
    }

    if($req->filled('url')){
      $an->url = $req->url;
    }

    $an->save();

    return redirect(route('admin.annc'))->with([
      'alert' => 'Added', 'a_type' => 'info'
    ]);
  }

  public function edit(Request $req){

  }

  public function delete(Request $req){
    if($req->filled('id')){
      $an = Announcement::find($req->id);

      if($an){
        $an->deleted_by = $req->user()->id;
        $an->save();
        $an->delete();

        return redirect(route('admin.annc'))->with([
          'alert' => 'Deleted', 'a_type' => 'info'
        ]);
      } else {
        return redirect(route('admin.annc'))->with([
          'alert' => 'Announcement 404', 'a_type' => 'warning'
        ]);
      }

    } else {
      return redirect(route('admin.annc'));
    }
  }
}
