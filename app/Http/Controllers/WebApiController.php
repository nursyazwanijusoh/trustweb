<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SkillType;
use App\CommonSkillset;
use App\common\IopHandler;
use App\User;
use App\Checkin;
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
          'div' => $user->unit
        ]);
      }
    }


    return $result;

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
        ->latest()->first();
      $tloc = '';
      if($cekin){
        $bd = $cekin->place->building;
        $tloc = $bd->floor_name . ' - ' . $bd->building_name;
      }
      $colid = 'd' . $value->format('md');
      $data[$colid] = $tloc;

    }

    // $data['t'] = $user->Section();

    return $data;

  }




}
