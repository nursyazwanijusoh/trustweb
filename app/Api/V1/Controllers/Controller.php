<?php
namespace App\Api\V1\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller as BaseController;
use \DateTime;
use QrCode;
use App\Subordinate;

/**
 * Shared functions will be placed here
 */
class Controller extends BaseController
{
  use Helpers;

  function errorHandler($errno, $errstr) {
		return $this->respond_json($errno, $errstr);
	}

  function getLOV(){
    return [
      'role' => [
        '0' => 'Super Admin',
        '1' => 'Floor Admin',
        '2' => 'VIP',
        '3' => 'Staff'
      ],
      'status' => [
        '0' => 'Inactive',
        '1' => 'Active/Free',
        '2' => 'Reserved',
        '3' => 'Occupied'
      ]
    ];
  }

	function respond_json($retCode, $message, $data_arr = []){
		$curtime = date("Y-m-d h:i:sa");
		$retval = [
			'code' => $retCode,
			'msg'  => $message,
			'time' => $curtime,
			'data' => $data_arr
		];

		return $retval;

	}


  function sendEmail(){
    set_error_handler(array($this, 'errorHandler'));
    mail('mohdamer.ahmad@tm.com.my', 'send from laravel', 'hai world!');
    return 'Done';
  }

  function home(){
    return 'Rumah Api';
  }

  function playground(){

    $nom = new LdapHelper;
    return $nom->getSubordinate('WOO WAI MUN');
    // $sum = \DB::table('activities')->where('task_id', 1)->sum('hours_spent');
    // $sum = \DB::table()

    // return $sum;
    // $subs = [
    //   0 => 'checked',
    //   2 => 'nom'
    // ];
    //
    // return $subs[1];

  }



}
