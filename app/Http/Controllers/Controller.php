<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function returnJsonToTextFile($filename, $data){

      $filecontent = "";

      foreach ($data as $value) {
        $oneline = "";
        foreach ($value as $item) {
          $oneline .= $item . ",";
        }
        $filecontent .= $oneline . "\r\n";
      }

      $headers = [
        'Content-type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
      ];

      return \Response::make($filecontent, 200, $headers);
    }
}
