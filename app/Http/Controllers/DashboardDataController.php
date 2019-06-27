<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Checkin;

class DashboardDataController extends Controller
{
    public function index(Request $req){
      if($req->filled('alert')){
        return view('report.dashdata', ['alert' => $req->alert]);
      }
      return view('report.dashdata');
    }

    public function fetch(Request $erq){
      $result = '';
      $data = User::all();
      $header = [
        'staff_no', 'name', 'checkin_time', 'checkout_time',
      ];


      foreach($data as $aa){
        $d = $aa->toArray();
        $result = $result . $this->arrayToCsv($d) . "\r\n";
      }
      // return $result;

      return response()->attachment($result, 'contoh.csv');
    }

    private function getCheckinData($startdate, $enddate){
      $data = Checkin::whereBetween('checkin_time', array($startdate, $enddate))->get();
      return $data;
    }

    private function arrayToCsv( array $fields, $delimiter = ';', $enclosure = '"' ) {

      $output = [];
      foreach($fields as $af){
        // check for empty / null field
        if($af === null){
          array_push($output, 'null');
          continue;
        }

        // check for fields that might contains the delimiter
        if(strpos($af, $delimiter) !== false){
          $af = $enclosure . $af . $enclosure;
        }

        array_push($output, $af);
      }

      return implode( $delimiter, $output );
    }
}
