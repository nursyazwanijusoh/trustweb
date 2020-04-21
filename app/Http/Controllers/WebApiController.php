<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SkillType;
use App\CommonSkillset;
use App\common\IopHandler;

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



}
