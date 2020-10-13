<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SkillType;
use App\CommonSkillset;
use App\common\IopHandler;
use App\common\GDWActions;
use App\User;
use App\Attendance;
use App\Checkin;
use App\place;
use App\GwdActivity;
use \DB;
use \Carbon\Carbon;

class WebApiController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }

  // temp apis
  public function SSApiGetType(Request $req){
    $types = SkillType::query();
    if($req->filled('cat') && $req->cat != 0){
      $types->where('skill_category_id', $req->cat);
    }

    return $types->orderBy('name')->get(['id', 'name']);
  }

  public function SSApiGetSkill(Request $req){
    $types = CommonSkillset::query();
    if($req->filled('type') && $req->type != 0){
      $types->where('skill_type_id', $req->type);
    }

    if($req->filled('cat') && $req->cat != 0){
      $types->where('skill_category_id', $req->cat);
    }

    return $types->orderBy('name')->get(['id', 'name']);
  }

  public function reverseGeo(Request $req){
    return IopHandler::ReverseGeo($req->lat, $req->lon);
  }

  public function getImage(Request $req){
    if($req->filled('staff_no')){
      return IopHandler::GetStaffImage($req->staff_no);
    }
  }

  public function select2FindStaff(Request $req){
    if($req->filled('input')){

    } else {
      return [];
    }

    $result = [];
    // first search by exact staff no
    $user = User::where('staff_no', $req->input)->first();

    if($user){
      array_push($result, [
        'id' => $user->id,
        'text' => $user->staff_no . ' - ' . $user->name,
        'title' => $user->unit
      ]);
    } else {
      // find by name
      $users = User::where('name', 'LIKE', "%".$req->input."%")->get();
      foreach($users as $user){
        array_push($result, [
          'id' => $user->id,
          'text' => $user->staff_no . ' - ' . $user->name,
          'title' => $user->unit
        ]);
      }
    }


    return ['results' => $result];
  }

  public function findstaff(Request $req){

    if($req->filled('input')){

    } else {
      return [];
    }

    $result = [];
    // first search by exact staff no
    $user = User::where('staff_no', $req->input)->first();

    if($user){
      array_push($result, [
        'id' => $user->id,
        'staff_no' => $user->staff_no,
        'name' => $user->name,
        'text' => $user->name,
        'div' => $user->unit
      ]);
    } else {
      // find by name
      $users = User::where('name', 'LIKE', "%".$req->input."%")->get();
      foreach($users as $user){
        array_push($result, [
          'id' => $user->id,
          'staff_no' => $user->staff_no,
          'name' => $user->name,
          'text' => $user->name,
          'div' => $user->unit
        ]);
      }
    }


    return $result;

  }

  public function getPersonalCheckApi(Request $req){
    $user = User::find($req->user_id);
    if($user){

    } else {
      abort(404);
    }

    $cdate = new Carbon($req->tdate);
    $ldate = new Carbon($req->fdate);
    $cdate->addSecond();
    $data = [];

    $data['staff'] = ['name' => $user->name, 'id' => $user->id];
    $data['staff_no'] = $user->staff_no;
    $data['unit'] = $user->unit;
    $data['teamab'] = $user->teamab;
    // $data['section'] = $user->Section();
    // $data['email'] = $user->email;

    $daterange = new \DatePeriod(
      $ldate,
      \DateInterval::createFromDateString('1 day'),
      $cdate
    );

    foreach ($daterange as $value) {
      $chin = '';
      $chout = '';
      $clin = '';
      $clout = '';

      // dapatkan 1st checkin untuk hari tu
      $cekin = Checkin::where('user_id', $user->id)
        ->whereDate('checkin_time', $value)->orderBy('checkin_time', 'asc')->first();

      if($cekin){
        $chin = (new Carbon($cekin->checkin_time))->toTimeString();
      }

      // dapatkan last checkout untuk hari tu
      $cekin = Checkin::where('user_id', $user->id)
        ->whereDate('checkout_time', $value)->orderBy('checkout_time', 'desc')->first();

      if($cekin){
        $chout = (new Carbon($cekin->checkout_time))->toTimeString();
      }

      // dapatkan 1st clock in untuk hari tu
      $cekin = Attendance::where('user_id', $user->id)
        ->whereDate('clockin_time', $value)->orderBy('clockin_time', 'asc')->first();

      if($cekin){
        $clin = (new Carbon($cekin->clockin_time))->toTimeString();
      }

      // dapatkan last checkout untuk hari tu
      $cekin = Attendance::where('user_id', $user->id)
        ->whereDate('clockout_time', $value)->orderBy('clockout_time', 'desc')->first();

      if($cekin){
        $clout = (new Carbon($cekin->clockout_time))->toTimeString();
      }

      $df = GDWActions::GetDailyPerfObj($user->id, $value);

      $colid = 'd' . $value->format('md');
      $data[$colid.'_cuti'] = $df->getCutiInfo();
      $data[$colid.'_chin'] = $chin;
      $data[$colid.'_chout'] = $chout;
      $data[$colid.'_clin'] = $clin;
      $data[$colid.'_clout'] = $clout;
      // $data[$colid] = [
      //   'chin' => $chin,
      //   'chout' => $chout,
      //   'clin' => $clin,
      //   'clout' => $clout
      // ];

    }

    return $data;
  }

  public function getPersonalLocApi(Request $req){
    $user = User::find($req->user_id);
    if($user){

    } else {
      abort(404);
    }

    $cdate = new Carbon($req->tdate);
    $ldate = new Carbon($req->fdate);
    $cdate->addSecond();
    $data = [];

    $data['name'] = $user->name;
    $data['staff_no'] = $user->staff_no;
    $data['division'] = $user->unit;
    $data['teamab'] = $user->teamab;
    // $data['section'] = $user->Section();
    // $data['email'] = $user->email;

    $daterange = new \DatePeriod(
      $ldate,
      \DateInterval::createFromDateString('1 day'),
      $cdate
    );

    foreach ($daterange as $value) {
      // dapatkan 1st checkin untuk hari tu
      $cekin = Checkin::where('user_id', $user->id)
        ->whereDate('checkin_time', $value)->first();
      $tloc = '';
      if($cekin){
        $bd = $cekin->place->building;
        $tloc = $bd->floor_name . ' - ' . $bd->building_name;
      } else {
        $df = GDWActions::GetDailyPerfObj($user->id, $value);
        $tloc = $df->getCutiInfo();
      }
      $colid = 'd' . $value->format('md');
      $data[$colid] = $tloc;

    }

    // $data['t'] = $user->Section();

    return $data;

  }

  public function getFloorCheckinSummary(Request $req){

    if($req->filled('pid')){

      $labels = [];
      $data = [];

      $cfloors = DB::table('places')
        ->join('users', 'places.checkin_staff_id', '=', 'users.id')
        ->join('units', 'users.unit_id', '=', 'units.id')
        ->select(DB::raw('count(*) as scount, units.pporgunitdesc'))
        ->where('places.building_id', $req->pid)
        ->where('users.status', 1)
        ->whereNotNull('users.curr_checkin')
        ->groupBy('units.pporgunitdesc')
        ->get();


      foreach($cfloors as $af){
        $labels[] = $af->pporgunitdesc;
        $data[] = $af->scount;
      }
    } else {
      abort(403);
    }
  }

  public function indivDetailRept(Request $req){
    if($req->filled('uid')){
      $user = User::find($req->uid);

      if($user){
        $sdate = new Carbon($req->startdate);
        $edate = new Carbon($req->enddate);

        $actlist = GwdActivity::where('user_id', $user->id)
          ->whereDate('activity_date', '>=', $sdate)
          ->whereDate('activity_date', '<=', $edate)
          ->get();
        $retdata = [];

        foreach($actlist as $ac){
          $retdata[] = [
            'name' => $user->name,
            'date' => $ac->activity_date,
            'tag' => $ac->ActCat->descr,
            'type' => $ac->ActType->descr,
            'title' => $ac->parent_number,
            'detail' => $ac->details,
            'hours' => $ac->hours_spent
          ];
        }

        return $retdata;

      } else {
        abort(404);
      }
    } else {
      abort(403);
    }
  }


}
