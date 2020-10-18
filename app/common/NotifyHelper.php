<?php

namespace App\common;

use App\User;
use GuzzleHttp\Client;

class NotifyHelper
{

    public static function SendPushNoti($noti_id, $title, $body, $data = []){

      if(!isset($noti_id)){
        return 'no noti id';
      }

      $client = new Client();
      $param = [
        'to' => $noti_id,
        'title' => $title,
        'body' => $body,
        // 'icon' => 'https://trust.tm.com.my/welcome/img/TrustNew.png',
        'badge' => 1
      ];
      $head = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'accept-encoding' => 'gzip, deflate',
        'host' => 'exp.host'
      ];

      // return $param;

      $resp = $client->request(
        'POST',
        'https://exp.host/--/api/v2/push/send', [
          // 'headers' => $head,
          'form_params' => $param
        ]

      );

      return $resp;

    }

    public static function SendBulkPushNoti($noti_ids, $title, $body, $data = []){

      if(sizeof($noti_ids) == 0){
        return 'no noti id';
      }

      $client = new Client();

      $param = [];

      foreach ($noti_ids as $key => $value) {
        $param[] = [
          'to' => $value,
          'title' => $title,
          'body' => $body,
          // 'icon' => 'https://trust.tm.com.my/welcome/img/TrustNew.png',
          'badge' => 1
        ];
      }

      // $param = [
      //   'to' => $noti_ids,
      //   'title' => $title,
      //   'body' => $body,
      //   // 'icon' => 'https://trust.tm.com.my/welcome/img/TrustNew.png',
      //   'badge' => 1
      // ];

      $head = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'accept-encoding' => 'gzip, deflate',
        'host' => 'exp.host'
      ];

      // return $param;

      $resp = $client->request(
        'POST',
        'https://exp.host/--/api/v2/push/send', [
          'headers' => $head,
          'json' => $param
        ]

      );

      return $resp;

    }
}
