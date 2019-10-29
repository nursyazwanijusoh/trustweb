<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PublicHoliday;

class PublicHolidayController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(){
    return view('admin.pubholiday',
      ['data' => PublicHoliday::all()->sortBy('event_date')]
    );
  }

  public function add(Request $req){




    return redirect(route('ph.list', [], false))->with(['alert' => $req->name . ' added']);


  }

  public function edit(Request $req){



  }

  public function delete(Request $req){



  }




}
