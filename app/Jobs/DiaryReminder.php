<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


use App\BatchJob;
use App\User;
use App\common\NotifyHelper;
use \Carbon\Carbon;
use App\common\GDWActions;

class DiaryReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bjobid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
      $bjob = new BatchJob;
      $bjob->job_type = 'Diary Reminder';
      $bjob->status = 'New';
      $bjob->save();

      $this->bjobid = $bjob->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $bjob = BatchJob::find($this->bjobid);

      if($bjob && $bjob->status == 'New'){
        // start process
        $bjob->status = 'Processing';
        $bjob->processed_at = now();
        $bjob->save();

        $status = "Completed";

        try {
          $ddate = date('Y-m-d');

          $stime = new Carbon;
      		$ulist = User::where('status', 1)->whereNotNull('pushnoti_id')->get();
      		// return $ulist->count();
      		$counter = 0;
      		foreach($ulist as $user){

      			// skip if pushnoti_id too short
      			if(strlen(trim($user->pushnoti_id)) < 10){
      				continue;
      			}

      			$df = GDWActions::GetDailyPerfObj($user->id, $ddate);
      			// skip if not expected to work
      			if($df->expected_hours == 0){
      				continue;
      			}

      			// skip if actual hours not 0
      			if($df->actual_hours != 0){
      				continue;
      			}

      			// send the push noti
      			$pmsg = 'Hi ' . $user->name . '. This is a reminder to update your diary entry. Please ignore this message if you have done so. TQ';
      			$aaa = NotifyHelper::SendPushNoti($user->pushnoti_id, 'Diary Reminder', $pmsg);
      			$respp = json_decode($aaa->getBody()->getContents());
      			// if($respp->data->status == 'error'){
      			// 	// remove the pushnoti_id if from this staff
      			// 	$onestaff->pushnoti_id = null;
      			// 	$onestaff->save();
      			// }


      			$counter++;
      		}

      		$etime = new Carbon;

      		$msg = json_encode( [
      			'start' => $stime->toDateTimeString(),
      			'end' => $etime->toDateTimeString(),
      			'count' => $counter
      		]);




        } catch(\Throwable $te){
          $status = 'Failed';
          $msg = $te->getMessage();
        }

        $bjob->status = $status;
        $bjob->extra_info = $msg;
        $bjob->completed_at = now();
        $bjob->save();
      }
    }
}
