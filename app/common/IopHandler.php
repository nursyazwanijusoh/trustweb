<?php

namespace App\common;

class IopHandler {

  static $profile_baseuri = "https://api.oip.tm.com.my/app/t/tmrnd.com.my/era/1.0.0/profile/";
  static $options = [
    'query' => ['api_key' => 'Z9HYE86CIElVjTEJuDOy2eBWPrL96et41wUmjL3M'],
    'headers' => ['Authorization' => 'Bearer f0fae581-2e13-3e6d-aeda-7cfe2c97a4f5']
  ];

  public static function FindStaffByName($name){
    $reclient = new Client(["base_uri" => self::$profile_baseuri]);

    $request = $reclient->request('GET', 'search/' . $name, $this->options)->getBody()->getContents();
    // $request->addHeader('Authorization: Bearer', '5a107934-68de-38cd-9a34-60fa4ae46267');
    // $resp = $reclient->send($request);

    $ret = json_decode($request);
    if(sizeof($ret) > 0){
      return $ret[0]['Staff_No'];
    } else {
      return null;
    }
  }

}
