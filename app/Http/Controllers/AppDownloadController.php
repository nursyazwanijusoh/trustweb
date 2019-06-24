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
    $ipa = \Storage::exists('public/tm_trust.ipa');
    $apk = \Storage::exists('public/tm_trust.apk');

    if($req->filled('alert')){
      return view('appdownload', ['alert' => $req->alert, 'ipa' => $ipa, 'apk' => $apk]);
    }

    return view('appdownload', ['ipa' => $ipa, 'apk' => $apk]);
  }

  public function upload(Request $req){
    $type = $req->type;
    $storedfile = $req->file('inputfile')->storeAs('public', 'tm_trust.' . $type);

    return redirect(route('app.list', ['alert' => $storedfile], false));

  }

  public function download(Request $req){
    return \Storage::download('public/tm_trust.' . $req->type, 'tm_trust.' . $req->type);
  }

  public function delete(Request $req){
    $msg = 'file deleted';
    if(\Storage::exists('public/tm_trust.' . $req->type)){
      \Storage::delete('public/tm_trust.' . $req->type);
    } else {
      $msg = 'public/tm_trust.' . $req->type . ' not exist';
    }

    return redirect(route('app.list', ['alert' => $msg], false));
  }
}
