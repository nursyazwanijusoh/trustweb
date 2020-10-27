<?php

namespace App\common;

use GuzzleHttp\Client;

class TribeApiCallHandler {

  static $baseuri = "https://trust.test/";

  public static function LogOut($staffno){
    $reclient = new Client(["base_uri" => self::$baseuri]);
    $options = [
      // 'headers' => ['Authorization' => env('TMOIP_TOKEN')]
    ];


    $request = $reclient->request('GET', 'api/', $options)->getBody()->getContents();

    // $response = response()->make($request, 200);
    // $response->header('Content-Type', 'image/jpeg'); // change this to the download content type.
    // return $response;
  }



}
