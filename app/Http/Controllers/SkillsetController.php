<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CommonSkillet;
use App\PersonalSkillet;

/*
common skillset index:



 */

class SkillsetController extends Controller
{

  function setCommonSkillset(Request $req){

  }

  function addPersonalSkillset(Request $req){

  }

  

  function viewCurrentSkillset(Request $req){
    $c_staff_id = Session::get('staffdata')['id'];
    if($req->filled('staff_id')){
      $c_staff_id = $req->staff_id;
    }

    $commonskills = array([
        'group_name' => 'Kumpulan 1',
        'skills' => array([

        ])
      ]);



    return view('skillset.index');

  }



}
