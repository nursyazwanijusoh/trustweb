<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\common\GDWActions;
use App\ActivityType;
use App\TaskCategory;
use App\GwdActivity;
use App\DailyPerformance;
use \Carbon\Carbon;

class GwdActivityController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function add(Request $req){
    $staffid = $req->session()->get('staffdata')['id'];
    // dd($req->all());
    $act = GDWActions::addActivity($req, $staffid);
    if($act == '402'){
      return redirect()->back()->withInput()->withErrors([
        'hours' => 'Exceeded current valid work hour'
      ]);
    }

    return redirect(route('staff.addact', ['dfid' => $act->daily_performance_id]))->with(['alert' => 'Diary entry added', 'a_type' => 'success']);
  }

  public function delete(Request $req){
    $staffid = $req->session()->get('staffdata')['id'];

    $taccccc = GwdActivity::find($req->actid);
    $df = $taccccc->daily_performance_id;
    if($taccccc){
      if($taccccc->user_id != $staffid){
        return redirect()->back()->with(['alert' => 'Not allowed to delete the entry of another person', 'a_type' => 'danger']);
      }
    }


    // dd($req->all());
    $act = GDWActions::deleteActivity($req->actid);

    return redirect(route('staff.addact', ['dfid' => $df]))->with(['alert' => 'Diary entry ' . $act, 'a_type' => 'warning']);
  }

  public function edit(Request $req){
    $act = GwdActivity::find($req->id);
    if($act){
      if($act->user_id != $req->user()->id){
        return redirect()->back()->with(['alert' => 'Cannot edit diary that belongs to other person', 'a_type' => 'danger']);
      }

      $currdp = $act->DailyPerf;
      $hoursdiff = $req->hours - $act->hours_spent;

      if(GDWActions::canAcceptThisAct($currdp, $hoursdiff)){
        $currdp->addHours($hoursdiff);

        $act->details = $req->details;
        $act->hours_spent = $req->hours;
        $act->save();

        return redirect(route('staff.addact', ['dfid' => $act->daily_performance_id]))->with(['alert' => 'Diary entry for ' . $act->parent_number . ' updated', 'a_type' => 'success']);
      } else {
        return redirect()->back()->with(['alert' => 'Exceeded valid current work hour', 'a_type' => 'danger']);
      }



    } else {
      return redirect()->back()->with(['alert' => 'Diary entry not found', 'a_type' => 'danger']);
    }
  }

  public function actinfo(Request $req){
    $act = GwdActivity::find($req->actid);
    if($act){
      return [
        'id' => $act->id,
        'at' => $act->ActCat->descr,
        'idn' => $act->parent_number,
        'ac' => $act->ActType->descr,
        'remark' => $act->details,
        'hours' => $act->hours_spent
      ];
    } else {
      abort(404);
    }
  }

  public function actdayinfo(Request $req){
    $seldf = GDWActions::GetDailyPerfObj($req->user()->id, $req->indate);
    if($seldf){
      return redirect(route('staff.addact', ['dfid' => $seldf->id]));
    } else {
      abort(404);
    }
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
    $isvisitor = false;

    if($req->filled('staff_id')){
      $isvisitor = ($staffid != $req->staff_id);
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
                 // 'borderColor' => '#000',
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

      // dd($isvisitor);

    return view('staff.gwdetail', [
      'damon' => $monmon,
      'staffid' => $staffid,
      'activities' => $activities,
      'curdate' => $today,
      'chart' => $schart,
      'isvisitor' => $isvisitor
    ]);

  }

  public function form(Request $req){
    $actype = ActivityType::where('status', 1)->get();
    $acats = TaskCategory::where('status', 1)->get();

    $cuserid = $req->user()->id;
    $isvisitor = false;

    $today = date('Y-m-d');
    $mindate = new Carbon($today);
    $mindate->subDays(7);

    $indate = date('Y-m-d');
    $tothrs = 0;
    $acttlist = [];

    if($req->filled('dfid')){
      $seldf = DailyPerformance::find($req->dfid);
      if($seldf){
        $seldf->recalcHours();
        $indate = $seldf->record_date;

        if($seldf->user_id != $cuserid){
          $isvisitor = true;
          $cuserid = $seldf->user_id;
        }
      } else {
        abort(404);
      }
    } else {
      $seldf = GDWActions::GetDailyPerfObj($cuserid, $today);
    }

    $earlytime = GDWActions::GetStartWorkTime($cuserid, $indate);

    $cindate = new Carbon($indate);
    // if($cindate->lt($mindate)){
    //   $isvisitor = true;
    // }

    $activities = GwdActivity::where('user_id', $cuserid)
      ->whereDate('activity_date', $indate)
      ->get();

    $tagref = [];
    foreach ($acats as $key => $value) {
      if($value->is_pbe){
        array_push($tagref, $value->descr);
      }
    }

    // check if now is before start time
    $nowtime = new Carbon;
    if($nowtime->lt($earlytime)){
      $isearly = true;
    } else {
      $isearly = false;
    }



    return view('staff.addactivity', [
      'actlist' => $actype,
      'curdate' => $today,
      'actcats' => $acats,
      'recdate' => $indate,
      'todayacts'    => $activities,
      'isvisitor' => $isvisitor,
      'pbes'    => [],
      'mindate' => $mindate->format('Y-m-d'),
      'tagref'  => json_encode($tagref),
      'dfobj' => $seldf,
      'early' => $earlytime,
      'isearly' => $isearly
    ]);
  }

}
