<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\common\GDWActions;
use App\ActivityType;
use App\TaskCategory;
use App\GwdActivity;
use \Carbon\Carbon;

class GwdActivityController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function add(Request $req){
    $staffid = $req->session()->get('staffdata')['id'];
    $act = GDWActions::addActivity($req, $staffid);

    return redirect(route('staff.addact', ['alert' => 'Activity added']));
  }

  public function cuti(Request $req){
    $staffid = $req->session()->get('staffdata')['id'];
    $act = GDWActions::setOnLeave($staffid, $req->date, $req->ctype);
    return redirect(route('staff.addact', ['alert' => 'Cuti registered']));
  }

  public function list(Request $req){
    // defaults
    $today = date('Y-m-d');
    $staffid = $req->session()->get('staffdata')['id'];


    if($req->filled('staff_id')){
      $staffid = $req->staff_id;
    }

    if($req->filled('actdate')){
      $today = $req->actdate;
    }

    // build the date range
    $cdate = Carbon::parse($today);
    $monmon = $cdate->format('F Y');
    $sdate = $cdate->startOfMonth()->toDateString();
    $edate = $cdate->addMonths(1)->toDateString();

    // get the chart data
    $gdata = GDWActions::getActSummary($staffid, $today);

    $schart = app()->chartjs
         ->name('bar')
         ->type('pie')
         ->size(['width' => 400, 'height' => 150])
         ->labels($gdata['label'])
         ->datasets([
             [
                 'label' => 'Hours Spent',
                 'backgroundColor' => $gdata['bg'],
                 'borderColor' => '#000',
                 'data' => $gdata['data']
             ]
         ])
         ->options([
           'responsive' => true,
           'title' => [
             'display' => true,
             'text' => 'Number of hours spent for ' . $monmon,
           ],
           'tooltips' => [
             'mode' => 'index',
             'intersect' => true,
           ],
           'hover' => [
             'mode' => 'nearest',
             'intersect' => true,
           ],
         ]);

    // get the list of activities
    $activities = GwdActivity::where('user_id', $staffid)
      ->whereDate('activity_date', '>=', $sdate)
      ->whereDate('activity_date', '<', $edate)
      ->get();

    return view('staff.gwdetail', [
      'damon' => $monmon,
      'staffid' => $staffid,
      'activities' => $activities,
      'curdate' => $today,
      'chart' => $schart
    ]);

  }

  public function form(Request $req){
    $actype = ActivityType::where('status', 1)->get();
    $acats = TaskCategory::where('status', 1)->get();
    $today = date('Y-m-d');

    if($req->filled('alert')){
      return view('staff.addactivity', ['actlist' => $actype, 'curdate' => $today, 'actcats' => $acats, 'alert' => $req->alert]);
    }

    return view('staff.addactivity', ['actlist' => $actype, 'curdate' => $today, 'actcats' => $acats]);
  }

}
