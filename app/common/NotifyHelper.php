<?php

namespace App\common;

use App\User;
use GuzzleHttp\Client;

class NotifyHelper
{

    public static function SendPushNoti($noti_id, $title, $body, $data = []){
      $client = new Client();
      $param = [
        'to' => $noti_id,
        'title' => $title,
        'body' => $body,
        // 'icon' => 'https://trust.tm.com.my/welcome/img/TrustNew.png',
        'badge' => 1,
        'data' => $data
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
}
