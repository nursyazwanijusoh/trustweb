<?php

namespace App\Api\V1\Controllers\Tribe;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Unit;
use App\place;
use App\building;
use App\reservation;
use App\ActivityType;
use App\Activity;
use App\CommonConfig;
use App\EventAttendance;
use App\ResourceRequest;
use App\DailyPerformance;
use \DateTime;
use \DateTimeZone;
use \DateInterval;
use App\common\HDReportHandler;
use App\common\UserRegisterHandler;
use App\common\GDWActions;
use App\common\NotifyHelper;
use App\common\TeamHelper;
use App\Api\V1\Controllers\BookingHelper;

class UserController extends Controller
{
   public function validateToken(Request $req){
      $input = app('request')->all();




     

    }


}
