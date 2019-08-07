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

    public function fetch(Request $req){

      $startdate = $req->fdate;
      $enddate = $req->todate;
      $filename = 'checkin_' . date_format(date_create($startdate), 'Ymd') . '_' . date_format(date_create($enddate), 'Ymd') . '.csv';

      $header = [
        'staff_no', 'name', 'checkin_time', 'checkout_time', 'seat_label', 'floor', 'building', 'division'
      ];
      $result = $this->arrayToCsv($header) . "\r\n";

      $data = $this->getCheckinData($startdate, $enddate);

      foreach($data as $aa){
        $result = $result . $this->arrayToCsv($aa) . "\r\n";
      }
      // return $result;

      return response()->attachment($result, $filename);
    }

    private function getCheckinData($startdate, $enddate){
      $data = Checkin::whereBetween('checkin_time', array($startdate, $enddate))->get();
      $toreturn = [];

      foreach($data as $ac){
        $div = $ac->User->isvendor == 1 ? $ac->User->Partner->comp_name : $ac->User->unit;
        if($ac->place){
          $dseat = $ac->place->label;
          $dfloor = $ac->place->building->floor_name;
          $dbuild = $ac->place->building->building_name;
        } else {
          $dseat = "Deleted";
          $dfloor = "Deleted";
          $dbuild = "Deleted";
        }

        $nu = [
          $ac->User->staff_no,
          $ac->User->name,
          $ac->checkin_time,
          $ac->checkout_time,
          $dseat,
          $dfloor,
          $dbuild,
          $div
        ];

        array_push($toreturn, $nu);
      }

      return $toreturn;
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
