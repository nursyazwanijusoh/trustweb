<?php

namespace App\Http\Controllers;

use App\OppProject;
use Illuminate\Http\Request;

/**
 * general controller for Opportunity
 */
class OpportunityController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }

    public function index(Request $req) {

    }

}
