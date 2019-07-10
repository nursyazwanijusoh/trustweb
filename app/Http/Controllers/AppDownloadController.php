<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AppDownloadController extends Controller
{
  public function list(Request $req){

    // dd(\Auth::user());

    // check for existing file:
    //
    $ipa = \Storage::exists('public/trust.ipa');
    $plist = \Storage::exists('public/trust.plist');
    $apk = \Storage::exists('public/trust.apk');

    if($req->filled('alert')){
      return view('appdownload', ['alert' => $req->alert, 'ipa' => $ipa, 'apk' => $apk, 'plist' => $plist]);
    }

    return view('appdownload', ['ipa' => $ipa, 'apk' => $apk, 'plist' => $plist]);
  }

  public function upload(Request $req){
    $type = $req->type;
    $storedfile = $req->file('inputfile')->storeAs('public', 'trust.' . $type);

    return redirect(route('app.list', ['alert' => $storedfile], false));

  }

  public function download(Request $req){
    return \Storage::download('public/trust.' . $req->type, 'trust.' . $req->type);
  }

  public function getipa(){
    return \Storage::download('public/trust.ipa', 'trust.ipa');
  }

  public function getplist(){
    return \Storage::download('public/trust.plist', 'trust.plist');
  }

  public function delete(Request $req){
    $msg = 'file deleted';
    if(\Storage::exists('public/trust.' . $req->type)){
      \Storage::delete('public/trust.' . $req->type);
    } else {
      $msg = 'public/trust.' . $req->type . ' not exist';
    }

    return redirect(route('app.list', ['alert' => $msg], false));
  }
}
