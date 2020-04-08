<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\common\HDReportHandler;
use \DB;
use App\Unit;
use App\building;

class AdminReportController extends Controller
{
  private $hdrh;

  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');

      $this->hdrh = new HDReportHandler;
  }

  public function DivByDateFind(Request $req){
    // get the registered list of division / unit
    $divrlist = DB::table('users')
      ->select('lob', DB::raw('count(*) as reg_count'))
      ->groupBy('lob')
      ->get();

    $divalist = [];

    // translate the div/unit
    foreach($divrlist as $adiv){
      $unitname = $adiv->lob;  // default, just in case
      if($adiv->lob === null){
        $unitname = 'Vendor';
      }
      $unit = Unit::where('pporgunit', $adiv->lob)->first();

      if($unit){
        $unitname = $unit->pporgunitdesc;
      }

      array_push($divalist, [
        'pporgunit' => $adiv->lob,
        'divname' => $unitname,
        'regcount' => $adiv->reg_count
      ]);
    }

    $data = [];

    if($req->filled('lob')){
      $data = $this->hdrh->getDivByDate($req->lob, $req->rptdate);
    }

    // return $data;

    return view('report.hddivday', ['divlist' => $divalist, 'sysdate' => date('Y-m-d')
        , 'data' => $data]);

  }

  public function WorkSpaceUsage(Request $req){
    $buildlist = building::where('status', '1')->get();
    $firstbuildid = 0;
    foreach($buildlist as $abuild){
      $firstbuildid = $abuild->id;
      break;
    }

    if($req->filled('build_id')){
      $data = $this->hdrh->getFloorUsage($req->build_id);
      $firstbuildid = $req->build_id;
    } else {
      if($buildlist->count() > 0){
        $data = $this->hdrh->getFloorUsage($firstbuildid);
      } else {
        $data = [];
      }

    }

    $data['buildlist'] = $buildlist;
    $data['selbuid'] = $firstbuildid;

    return view('report.hdwsu', $data);


  }

  public function fridayulist(Request $req){
    $eight = Unit::where('friday_hours', 8)->get();
    $seven = Unit::where('friday_hours', 7.5)->get();

    return view('admin.fridayhours', [
      'lapan' => $eight,
      'tujuh' => $seven
    ]);
  }

  public function addfriday8(Request $req){

    $unit = Unit::find($req->id);
    if($unit){
      $unit->friday_hours = 8;
      $unit->save();
    }
    return redirect(route('admin.fridayhours'));
  }

  public function delfriday8(Request $req){

    $unit = Unit::find($req->id);
    if($unit){
      $unit->friday_hours = 7.5;
      $unit->save();
    }

    return redirect(route('admin.fridayhours'));
  }

}
