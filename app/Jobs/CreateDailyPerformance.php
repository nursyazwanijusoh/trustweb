<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\User;
use App\DailyPerformance;
use App\BatchJob;
use App\common\GDWActions;

class CreateDailyPerformance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $input_date;
    protected $bjobid;

    public $tries = 1;
    public $timeout = 7200;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($idate)
    {
      $this->input_date = $idate;

      $bjob = new BatchJob;
      $bjob->job_type = 'Create daily performance';
      $bjob->status = 'New';
      $bjob->from_date = $idate;
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

        $ret = $this->doTheProcessHere();

        $bjob->status = $ret['status'];
        $bjob->extra_info = 'record count ' . $ret['rec_count'];
        $bjob->completed_at = now();
        $bjob->save();
      }
    }

    public function doTheProcessHere(){
      $counter = 0;

  		$userrs = User::whereNotNull('lob')
  			->where('status', 1)
  			->where('isvendor', false)
  			->whereRaw('LENGTH(lob) > 6')
  			->get();

  		foreach ($userrs as $key => $value) {
  			$gdata = DailyPerformance::where('user_id', $value->id)
  				->whereDate('record_date', $this->input_date)
  				->first();
  			if($gdata){
  				// got data. do nothing
  			} else {
  				// no data. create new
  				if(isset($value->lob) && strlen($value->lob) > 6){
  					$dp = new DailyPerformance;
  		      $dp->user_id = $value->id;
  		      $dp->record_date = $this->input_date;
  		      $dp->unit_id = $value->lob;
  		      $dp->expected_hours = GDWActions::GetExpectedHours($this->input_date, $dp);
  		      $dp->save();

  					$counter++;
  				}

  			}
  		}

      return [
        'status' => 'Completed',
        'rec_count' => $counter
      ];

    }
}
