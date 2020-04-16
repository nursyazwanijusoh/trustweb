<?php

namespace App\common;

use GuzzleHttp\Client;

class IopHandler {

  static $baseuri = "https://tmoip.tm.com.my/api/t/tm.com.my/";
  // reverse geo   https://tmoip.tm.com.my/api/t/tm.com.my/geosmartmap/1.0.0/search/reversegeocode?lat=2.788489299&lon=101.7182277
  // pic           https://tmoip.tm.com.my/api/t/tm.com.my/era/1.0.0/profile/image/S54113

  public static function GetStaffImage($staffno){
    $reclient = new Client(["base_uri" => self::$baseuri]);
    $options = [
      'headers' => ['Authorization' => env('TMOIP_TOKEN')]
    ];


    $request = $reclient->request('GET', 'era/1.0.0/profile/image/' . $staffno, $options)->getBody()->getContents();

    $response = response()->make($request, 200);
    $response->header('Content-Type', 'image/jpeg'); // change this to the download content type.
    return $response;
  }

  public static function ReverseGeo($lat, $long){
    $reclient = new Client(["base_uri" => self::$baseuri]);
    $options = [
      'query' => ['lat' => $lat, 'lon' => $long],
      'headers' => ['Custom' => env('TMOIP_TOKEN')]
    ];


    $request = $reclient->request('GET', 'geosmartmap/1.0.0/search/reversegeocode', $options)->getBody()->getContents();
    // $request->addHeader('Authorization: Bearer', '5a107934-68de-38cd-9a34-60fa4ae46267');
    // $resp = $reclient->send($request);

    $ret = json_decode($request);

    if(sizeof($ret) > 0){
      return $ret[0]->formatted_address;
    } else {
      return "No result";
    }
  }

}
