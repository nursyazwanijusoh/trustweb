<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PublicHoliday;
use App\DailyPerformance;
use App\common\GDWActions;
use \Calendar;

class PublicHolidayController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('AdminGate');
  }

  public function list(){
    $phs = PublicHoliday::all();
    $counter = rand(0, 12);

    // build the calendar
    $evlist = [];
    foreach($phs as $value){
      $counter++;
      $evlist[] = Calendar::event(
        $value->name,
        true,
        $value->event_date,
        $value->event_date,
        $value->id,[
          'color' => GDWActions::getBgColor($counter)
        ]
      );
    }

    $cds = Calendar::addEvents($evlist);

    return view('admin.pubholiday',
      ['data' => $phs, 'cal' => $cds]
    );
  }

  public function add(Request $req){

    $nuph = new PublicHoliday;
    $nuph->event_date = $req->event_date;
    $nuph->name = $req->name;
    $nuph->created_by = $req->user()->id;
    $nuph->save();

    // update the dailyperf if exist
    DailyPerformance::whereDate('record_date', $req->event_date)
      ->update([
        'expected_hours' => 0,
        'is_public_holiday' => true,
        'public_holiday_id' => $nuph->id
      ]);


    return redirect(route('ph.list', [], false))->with(['alert' => $req->name . ' added']);
  }

  public function edit(Request $req){
    $oph = PublicHoliday::find($req->id);

    if($oph){
      $oph->name = $req->name;
      $oph->save();
      return redirect(route('ph.list', [], false))->with(['alert' => $req->name . ' updated']);
    } else {
      return redirect(route('ph.list', [], false))->with(['alert' => 'Event no longer exist']);
    }
  }

  public function del(Request $req){

    $oph = PublicHoliday::find($req->id);

    if($oph){
      $ename = $oph->name;

      // find if there is any other PH on the same day
      $otherph = PublicHoliday::whereDate('event_date', $oph->event_date)
        ->where('id', '!=', $oph->id)
        ->first();

      if($otherph){
        // got other ph. just change the pointing to this ph
        DailyPerformance::where('public_holiday_id', $oph->id)
          ->update(['public_holiday_id' => $otherph->id]);
      } else {
        // no other ph
        // remove ph reference for those who are on leave
        DailyPerformance::where('public_holiday_id', $oph->id)
          ->where('is_off_day', true)
          ->update([
            'public_holiday_id' => null,
            'is_public_holiday' => false
          ]);

        // then update with proper expected hours for those who are not on leave
        $exptHrs = GDWActions::GetExpectedHours($oph->event_date, null, $oph->id);
        // dd($exptHrs);
        DailyPerformance::where('public_holiday_id', $oph->id)
          ->where('is_off_day', false)
          ->update([
            'public_holiday_id' => null,
            'is_public_holiday' => false,
            'expected_hours' => $exptHrs
          ]);
      }

      // finally, delete the ph
      $oph->deleted_by = $req->user()->id;
      $oph->save();
      $oph->delete();

      return redirect(route('ph.list', [], false))->with(['alert' => $ename . ' deleted']);
    } else {
      return redirect(route('ph.list', [], false))->with(['alert' => 'Event no longer exist']);
    }

  }




}
