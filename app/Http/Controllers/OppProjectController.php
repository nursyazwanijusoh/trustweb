<?php

namespace App\Http\Controllers;

use App\OppProject;
use \Carbon\Carbon;
use Illuminate\Http\Request;

class OppProjectController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AgileResTeamGate');
  }

    public function list(Request $req) {
      $projects = OppProject::all();

      return view('opp.project.list', [
        'pojeks' => $projects
      ]);

    }

    public function create(Request $req) {
      $tom = new Carbon;
      $tom->addDay();
      return view('opp.project.create', [
        'tomorrow' => $tom->toDateString()
      ]);
    }

    public function view(Request $req) {

    }

    public function add(Request $req) {

      $pm =
    }

    public function edit(Request $req) {

    }

    public function del(Request $req) {

    }

}
