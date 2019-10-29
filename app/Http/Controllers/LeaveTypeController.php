<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveType;

class LeaveTypeController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(){
    return view('admin.pubholiday',
      ['data' => LeaveType::all()]
    );
  }

  public function add(Request $req){



  }

  public function edit(Request $req){



  }

  public function delete(Request $req){



  }
}
