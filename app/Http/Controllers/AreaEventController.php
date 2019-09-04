<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\common\GDWActions;
use App\place;
use App\User;
use App\AreaEvent;
use App\EventAttendance;
use \Calendar;
use \DateTime;
use \DateInterval;
use \DatePeriod;
use \Carbon\Carbon;

class AreaEventController extends Controller
{

  public function __construct(){
      $this->middleware('auth');
  }

  public function index(){
    $arealist = place::where('seat_type', 2)->get();

    return view('events.meetroom', ['data' => $arealist]);

  }

  public function areaEventCalendar(Request $req){

    if(!$req->filled('id')){
      return redirect()->route('area.list');
    }

    $marea = place::findOrFail($req->id);


    $lastmon = date('Y-m-d', strtotime('-1 month'));
    $today = date('Y-m-d');
    $stime = date('H:i');
    $etime = date('H:i', strtotime('1 hour'));
    $evs = AreaEvent::where('place_id', $req->id)
      ->whereDate('event_date', '>=', $lastmon)
      ->where('status', 'Active')
      ->get();

    $evlist = [];
    $counter = rand(0, 12);

    foreach ($evs as $key => $value) {
      $counter++;
      $evlist[] = Calendar::event(
        $value->event_name,
        $value->isfullday,
        // false,
        new \DateTime($value->start_time),
        new \DateTime($value->end_time),
        $value->id,[
          'url' => route('area.evdetail', ['id' => $value->id], false),
          'color' => GDWActions::getBgColor($counter)
        ]
      );
    }

    $cds = Calendar::addEvents($evlist);


    return view('events.areacalendar', [
      'curdate' => $today,
      'stime' => $stime,
      'etime' => $etime,
      'marea' => $marea,
      'cds' => $cds
    ]);
  }

  public function addEvent(Request $req){
    $aplace = place::find($req->area_id);
    if($aplace){

    } else {
      return redirect()->back()->withErrors(['msg' => 'Selected area no longer exist']);
    }

    // check for whole day events
    $ismultiday = false;
    if($req->filled('fullday')){
      $ismultiday = true;
      $stime = $req->fstartdate;
      $eventdate = $stime;
      $tenddate = Carbon::parse($req->fenddate);
      // add 1 day to the end date
      $etime = $tenddate->addDay()->toDateString();

    } else {
      $eventdate = $req->pevdate;
      $stime = $eventdate . ' ' . $req->pstime . ':00';
      $etime = $eventdate . ' ' . $req->petime . ':00';
    }

    // reject end time before start time
    $carbon_stime = Carbon::parse($stime);
    $carbon_etime = Carbon::parse($etime);

    if($carbon_stime->greaterThan($carbon_etime)){
      return redirect()->back()
        ->withErrors(['msg' => 'Start time is after end time'])
        ->withInput($req->input());
    }

    // check for overlapping request
    $olapeventcount = AreaEvent::where('status', 'Active')
      ->where('place_id', $aplace->id)
      ->where(function ($q) use ($etime, $stime){
        $q->where('start_time', '<', $etime);
        $q->where('end_time', '>', $stime);
      })
      ->count();

    // dd($olapeventcount);

    if($olapeventcount > 0){
      return redirect()->back()
        ->withErrors(['msg' => 'Overlapped with ' . $olapeventcount . ' event(s)'])
        ->withInput($req->input());
    }

    // no issue, create the event

    $aevent = new AreaEvent;
    $aevent->event_name = $req->event_name;
    $aevent->organizer_id = $req->user()->id;
    $aevent->place_id = $aplace->id;
    $aevent->building_id = $aplace->building->id;
    $aevent->event_date = $eventdate;
    $aevent->start_time = $stime;
    $aevent->end_time = $etime;
    $aevent->save();

    // then create the attendance holder

    if($ismultiday){
      $daterange = new DatePeriod(
        new DateTime($stime),
        DateInterval::createFromDateString('1 day'),
        new DateTime($etime)
      );

      $t = [];

      $intcount = 0;
      foreach($daterange as $onedate){
        $intcount++;
        $attend = new EventAttendance;
        $attend->area_event_id = $aevent->id;
        $attend->event_date = $onedate->format('Y-m-d');
        $attend->name = $req->event_name . ' day ' . $intcount;
        $attend->save();
      }

    } else {
      $attend = new EventAttendance;
      $attend->area_event_id = $aevent->id;
      $attend->event_date = $eventdate;
      $attend->name = $req->event_name;
      $attend->save();
    }


    return redirect(route('area.cal', ['id' => $aplace->id], false));
  }

  public function areaEventDetail(Request $req){

    $aevent = AreaEvent::findOrFail($req->id);
    $headers = ['Name', 'Email', 'Division'];
    $daycount = 0;
    $attendeelist = [];
    $today = date('Y-m-d');
    $reachedtoday = false;

    // find the attendance holder
    $eventdays = $aevent->EventDay;

    foreach ($eventdays as $key => $oneventday) {
      // add the date to the header
      $cdate = Carbon::parse($oneventday->event_date);
      array_push($headers, $cdate->format('D j-M'));
      $participant = $oneventday->Attendee;

      // if got participant
      if($participant){
        // go through the list of participant for this day
        foreach ($participant as $key => $onecheckin) {
          // check if this user already exist in the attendeelist
          $thisattendeefound = false;
          foreach ($attendeelist as $key => $oneuser) {
            if($oneuser->id == $onecheckin->user_id){
              $thisattendeefound = true;
              // add today's attendance
              $curatt = $oneuser->day_attended;
              array_push($curatt, 1);
              $oneuser->day_attended = $curatt;
            }
          }

          if($thisattendeefound == false){
            // user not in the list yet
            $newuser = User::find($onecheckin->user_id);
            // create the day_attended list for past days
            $notattend = [];
            for ($i=0; $i < $daycount; $i++) {
              array_push($notattend, 0);
            }

            // then push today's attendance
            array_push($notattend, 1);

            // assign this attendance to the user
            $newuser->day_attended = $notattend;

            // and add this user to the attendee list
            array_push($attendeelist, $newuser);
          }

        }

      }

      // increment the day counter
      $daycount++;

      // go through the attendee list again to mark those who didnt attend today
      foreach ($attendeelist as $key => $oneuser) {
        $curatt = $oneuser->day_attended;
        // if count of marked day not equal to daycount, meaning this user doesnt attend today
        if(count($curatt) != $daycount){

          if($reachedtoday == true){
            array_push($curatt, 2);
          } else {
            // mark it as absence
            array_push($curatt, 0);
          }

          // assign it back to the user
          $oneuser->day_attended = $curatt;
        }
      }

      if($today == $oneventday->event_date){
        $reachedtoday = true;
        // dd('istoday');
      }
    }

    // check if current user is admin
    if($req->user()->role == 0){
      $isadmin = true;
    } elseif($req->user()->role == 1){
      $floorid = $aevent->Location->building->id;
      if(isset($req->user()->allowed_building)){
        if(in_array($floorid, json_decode($req->user()->allowed_building))){
          $isadmin = true;
        } else {
          $isadmin = false;
        }
      } else {
        $isadmin = false;
      }

    } else {
      $isadmin = false;
    }

    // check if is owner of this event
    if($aevent->organizer_id == $req->user()->id){
      $isowner = true;
    } else {
      $isowner = false;
    }

    return view('events.details', [
      'headers' => $headers,
      'attendees' => $attendeelist,
      'eventinfo' => $aevent,
      'isadmin' => $isadmin,
      'isowner' => $isowner
    ]);
  }

  public function myevents(Request $req){
    $uid = $req->user()->id;
    if($req->filled('id')){
      $uid = $req->id;
    }

    $evlist = AreaEvent::where('organizer_id', $uid)->get();

    return view('events.myevents', ['elist' => $evlist]);

  }

  public function cancelEvent(Request $req){
    if(!$req->filled('id')){
      abort(400);
    }

    $aevent = AreaEvent::findOrFail($req->id);

    // double check if it's the owner
    if($req->user()->id != $aevent->organizer_id){
      abort(403);
    }

    $aevent->status = 'Cancelled';
    $aevent->save();

    return redirect(route('area.myevents', [], false));

  }

  public function rejectEvent(Request $req){
    if(!$req->filled('id')){
      abort(400);
    }

    $aevent = AreaEvent::findOrFail($req->id);

    // double check if it's not admin
    if($req->user()->role > 1){
      abort(403);
    }

    $aevent->status = 'Rejected';
    $aevent->admin_id = $req->user()->id;
    $aevent->admin_remark = $req->remark;
    $aevent->save();

    return redirect(route('area.cal', ['id' => $aevent->Location->building_id], false));

  }

}
