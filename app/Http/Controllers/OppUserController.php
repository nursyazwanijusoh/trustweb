<?php

namespace App\Http\Controllers;
use App\OppProject;

use Illuminate\Http\Request;

class OppUserController extends Controller
{
    //

    public function projectList(Request $req) {
        $projects = OppProject::all();
  
        return view('opp.user.projlist', [
          'pojeks' => $projects
        ]);
  
      }
}
