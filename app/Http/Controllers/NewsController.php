<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;

class NewsController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(){

    $news = News::all();

    return view('admin.news', ['news' => $news]);
  }

  public function detail(Request $req){
    if($req->filled('nid')){
      $news = News::find($req->nid);
      if($news){
        return $news->content;
      } else {
        abort(404);
      }
    } else {
      abort(403);
    }
  }

  public function add(Request $req){
    $an = new News;
    $an->title = $req->title;
    $an->content = $req->content;
    $an->user_id = $req->user()->id;
    $an->save();

    return redirect(route('admin.news.list'))->with([
      'alert' => 'News Added', 'a_type' => 'info'
    ]);
  }

  public function del(Request $req){
    if($req->filled('id')){
      $an = News::find($req->id);

      if($an){
        $an->deleted_by = $req->user()->id;
        $an->save();
        $an->delete();

        return redirect(route('admin.news.list'))->with([
          'alert' => 'Deleted', 'a_type' => 'info'
        ]);
      } else {
        return redirect(route('admin.news.list'))->with([
          'alert' => 'News 404', 'a_type' => 'warning'
        ]);
      }

    } else {
      return redirect(route('admin.news.list'));
    }
  }


}
