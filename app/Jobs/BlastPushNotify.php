<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

use App\User;
use App\PushAnnouncement;
use App\BatchJob;
use App\common\NotifyHelper;

class BlastPushNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pn_id;
    protected $bjobid;

    public $tries = 1;
    public $timeout = 7200;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pn_aidi)
    {
      $this->pn_id = $pn_aidi;

      $bjob = new BatchJob;
      $bjob->job_type = 'Blast Notification';
      $bjob->status = 'New';
      $bjob->obj_id = $pn_aidi;
      $bjob->class_name = 'PushAnnouncement';
      $bjob->save();

      $this->bjobid = $bjob->id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle2()
    {
      $bjob = BatchJob::find($this->bjobid);
      $bjob->status = 'Processing';
      $bjob->processed_at = now();
      $bjob->save();

      $status = 'Failed';
      $count = 0;
      $msg = '';
      $processed_data = [];

      $pn = PushAnnouncement::find($this->pn_id);

      if($pn){

        if($pn->status == 'N'){
          try {
            $pnids = [];

            $pn->status = 'P';
            $pn->save();

            if($pn->is_global){
              // send for everyone
              $stafflist = User::whereNotNull('pushnoti_id')->where('status', 1)->get();
              foreach($stafflist as $onestaff){
                if(strlen(trim($onestaff->pushnoti_id)) > 8 && $onestaff->status == 1){
                  $count++;
                  $pnids[] = $onestaff->pushnoti_id;

                  if(count($pnids) == 50){
                    $processed_data[] = NotifyHelper::SendBulkPushNoti($pnids, $pn->title, $pn->body);

                    $pn->rec_count = $count;
                    $pn->save();
                    $pnids = [];
                  }
                } else {
                  $onestaff->pushnoti_id = null;
                  $onestaff->save();
                }
              }
            } else {
              foreach ($pn->Groups as $grp) {
                // dd($grp->TheGroup);
                foreach($grp->Divisions() as $ondiv){
                  $stafflist = $ondiv->StaffWithNotiID;

                  foreach($stafflist->all() as $onestaff){
                    if(strlen(trim($onestaff->pushnoti_id)) > 8 && $onestaff->status == 1){
                      $count++;
                      $pnids[] = $onestaff->pushnoti_id;

                      if(count($pnids) == 50){
                        $processed_data[] = NotifyHelper::SendBulkPushNoti($pnids, $pn->title, $pn->body);
                        $pn->rec_count = $count;
                        $pn->save();
                        $pnids = [];
                      }
                    } else {
                      $onestaff->pushnoti_id = null;
                      $onestaff->save();
                    }
                  }
                }
              }
            }

            if(count($pnids) > 0){
              $processed_data[] = NotifyHelper::SendBulkPushNoti($pnids, $pn->title, $pn->body);
              $pnids = [];
            }

            $status = 'Completed';
            $msg = 'Notification sent';

            $pn->status = 'C';
            $pn->rec_count = $count;
            $pn->save();
          } catch(\Throwable $te){
            $status = 'Failed';
            $msg = $te->getMessage();
            $processed_data = $pnids;
          }
        } else {
          $msg = 'Notification already sent';
        }

      } else {
        $msg = 'pn_id 404';
      }

      $bjob->status = $status;
      $bjob->extra_info = json_encode([
        'msg' => $msg,
        'data' => $processed_data
      ]);
      $bjob->completed_at = now();
      $bjob->save();

    }

    /**
    *  original code
    */
    public function handle()
    {
      $bjob = BatchJob::find($this->bjobid);
      $bjob->status = 'Processing';
      $bjob->processed_at = now();
      $bjob->save();

      $status = 'Failed';
      $count = 0;
      $msg = '';
      $errlist = [];

      $pn = PushAnnouncement::find($this->pn_id);

      if($pn){

          if($pn->status == 'N'){

            $pn->status = 'P';
            $pn->save();

            if($pn->is_global){
              // send for everyone
              $stafflist = User::whereNotNull('pushnoti_id')->where('status', 1)->get();
              foreach($stafflist as $onestaff){
                if(strlen(trim($onestaff->pushnoti_id)) > 8 && $onestaff->status == 1){
                  $count++;
                  try {
                    $aaa = NotifyHelper::SendPushNoti($onestaff->pushnoti_id, $pn->title, $pn->body);
                    $respp = json_decode($aaa->getBody()->getContents());
                    if($respp->data->status == 'error'){
                      // remove the pushnoti_id if from this staff
                      $onestaff->pushnoti_id = null;
                      $onestaff->save();
                    }
                  } catch(\Throwable $te){
                    $errlist[] = [$onestaff->id => $te->getMessage()];
                  }

                  if($count % 50 == 0){
                    $pn->rec_count = $count;
                    $pn->save();
                  }
                }
              }
            } else {
              foreach ($pn->Groups as $grp) {
                // dd($grp->TheGroup);
                foreach($grp->Divisions() as $ondiv){
                  $stafflist = $ondiv->StaffWithNotiID;

                  foreach($stafflist->all() as $onestaff){
                    if(strlen(trim($onestaff->pushnoti_id)) > 8 && $onestaff->status == 1){
                      $count++;

                      try {
                        $aaa = NotifyHelper::SendPushNoti($onestaff->pushnoti_id, $pn->title, $pn->body);
                        $respp = json_decode($aaa->getBody()->getContents());
                        if($respp->data->status == 'error'){
                          // remove the pushnoti_id if from this staff
                          // $onestaff->pushnoti_id = null;
                          // $onestaff->save();
                          $errlist[] = [$onestaff->id => $respp->data];
                        }
                      } catch(\Throwable $te){
                        $errlist[] = [$onestaff->id => $te->getMessage()];
                      }


                      if($count % 50 == 0){
                        $pn->rec_count = $count;
                        $pn->save();
                      }
                    }
                  }
                }
              }
            }

            $status = 'Completed';
            $msg = 'Notification sent. Errors: ' . json_encode($errlist);

            $pn->status = 'C';
            $pn->rec_count = $count;
            $pn->save();
          } else {
            $msg = 'Notification already sent';
          }

      } else {
        $msg = 'pn_id 404';
      }



      $bjob->status = $status;
      $bjob->extra_info = $msg;
      $bjob->completed_at = now();
      $bjob->save();

    }
}
