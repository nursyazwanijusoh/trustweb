<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Poll;
use App\PollOption;

class PollController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index(Request $req){

    $recent = Poll::where('status', 'Active')
      ->where('public', true)
      ->orderBy('created_at', 'DESC')
      ->limit(3)
      ->get();

    return view('polls.index', [
      'newp' => $recent
    ]);
  }

  public function mypolls(Request $req){

    $polls = Poll::where('user_id', $req->user()->id)->get();

    return view('polls.list', [
      'polls' => $polls
    ]);
  }

  public function createPoll(Request $req){

    $tomorrow = new Carbon;
    $tomorrow->addDay();

    return view('polls.create', [
      'tomorrow' => $tomorrow->toDateString()
    ]);
  }

  public function docreatePoll(Request $req){
    $po = new Poll;
    $po->user_id = $req->user()->id;
    $po->title = $req->title;
    $po->description = $req->desc;
    if($req->filled('actdate')){
      $po->end_time = $req->actdate;
    }

    if($req->has('privatepoll')){
      $po->public = false;
    } else {
      $po->public = true;
    }

    $po->save();

    return redirect(route('poll.view', ['pid' => $po->id]));
  }

  public function deletePoll(Request $req){
    if($req->filled('pid')){
      $po = Poll::find($req->pid);
      if($po){

        if($req->user()->role < 2 || $po->user_id == $req->user()->id){
          $po->status = 'Closed';
          $po->save();

          return redirect()->back()->with([
            'a_type' => 'success',
            'alert' => 'poll closed'
          ]);
        } else {
          abort(403);
        }

      } else {
        return redirect(route('poll.index'))->with([
          'a_type' => 'warning',
          'alert' => 'poll not found'
        ]);
      }
    } else {
      return redirect(route('poll.index'));
    }
  }

  public function publishPoll(Request $req){
    if($req->filled('pid')){
      $po = Poll::find($req->pid);
      if($po){

        $po->status = 'Active';
        $po->save();

        return redirect()->back()->with([
          'a_type' => 'success',
          'alert' => 'poll published'
        ]);

      } else {
        return redirect(route('poll.index'))->with([
          'a_type' => 'warning',
          'alert' => 'poll not found'
        ]);
      }
    } else {
      return redirect(route('poll.index'));
    }
  }

  public function addOption(Request $req){
    if($req->filled('pid')){
      $po = Poll::find($req->pid);
      if($po){

        $opt = new PollOption;
        $opt->label = $req->optlable;
        $opt->description = $req->details;
        $opt->poll_id = $req->pid;
        $opt->save();

        return redirect()->back()->with([
          'a_type' => 'success',
          'alert' => 'poll option added'
        ]);

      } else {
        return redirect(route('poll.index'))->with([
          'a_type' => 'warning',
          'alert' => 'poll not found'
        ]);
      }
    } else {
      return redirect(route('poll.index'));
    }
  }

  public function removeOption(Request $req){
    $opt = PollOption::find($req->poid);
    if($opt){
      if($opt->poll->user_id == $req->user()->id){
        $opt->delete();
        return redirect()->back()->with([
          'a_type' => 'warning',
          'alert' => 'Poll option removed'
        ]);
      } else {
        return redirect()->back()->with([
          'a_type' => 'warning',
          'alert' => 'you are not the owner'
        ]);
      }
    } else {
      return redirect()->back();
    }
  }

  public function viewPoll(Request $req){
    if($req->filled('pid')){
      $po = Poll::find($req->pid);
      if($po){

        if($po->status != 'Draft'){
          $graphdata = $this->getGraph($po);
          // dd($graphdata);
        } else {
          $graphdata = false;
        }

        if($po->user_id == $req->user()->id){
          return view('polls.polladdopt', [
            'poll' => $po, 'graph' => $graphdata
          ]);
        } else {

          // check if already voted
          $voted = $req->user()->Votes->where('id', $req->pid)->count() != 0;

          if($po->status != 'Active'){
            $voted = true;
          }

          return view('polls.vote', ['poll' => $po, 'voted' => $voted, 'graph' => $graphdata]);
        }


      } else {
        return redirect(route('poll.index'))->with([
          'a_type' => 'warning',
          'alert' => 'poll not found'
        ]);
      }
    } else {
      return redirect(route('poll.index'));
    }

  }

  public function vote(Request $req){

    $po = Poll::find($req->pid);
    if($po){
      $voted = $req->user()->Votes->where('id', $req->pid)->count() != 0;
      if($voted){
        return redirect()->back()->with([
          'a_type' => 'warning',
          'alert' => 'You have already voted for this poll'
        ]);
      }

      $opt = PollOption::find($req->voteid);
      if($opt){
        // set this user as voted
        $po->Users()->attach($req->user());

        // then assign the vote option
        $opt->Users()->attach($req->user());

        return redirect()->back()->with([
          'a_type' => 'success',
          'alert' => 'Thank you for your vote'
        ]);

      } else {
        return redirect()->back()->with([
          'a_type' => 'warning',
          'alert' => 'Invalid poll option'
        ]);
      }


    } else {
      return redirect(route('poll.index'))->with([
        'a_type' => 'warning',
        'alert' => 'poll not found'
      ]);
    }

  }

  private function getGraph($poll){
    $counter = 0;
    $label = [];
    $value = [];
    // $bgcolor = ['rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)'];

    foreach($poll->options as $prtn){
      $counter++;
      array_push($label, $prtn->label);
      array_push($value, $prtn->Users->count());
      // if(($counter % 2) == 1){
      //   array_push($bgcolor, 'rgba(255, 99, 132, 0.6)');
      // } else {
      //   array_push($bgcolor, 'rgba(75, 192, 192, 0.6)');
      // }
    }

    if(count($value) < 4){
      $heighttt = 200;
      $typec = 'bar';
    } else {
      $heighttt = 140 + (30 * count($value));
      $typec = 'horizontalBar';
    }


    $schart = app()->chartjs
         ->name('barChartTest')
         ->type($typec)
         ->size(['width' => 400, 'height' => $heighttt])
         ->labels($label)
         ->datasets([
             [
                 "label" => "Votes",
                 'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                 'data' => $value
             ]
         ])
         ->options([
           'responsive' => true,
           'maintainAspectRatio' => false,
           'title' => [
             'display' => true,
             'text' => $poll->title,
           ],
           'tooltips' => [
             'mode' => 'index',
             'intersect' => false,
           ],
           'hover' => [
             'mode' => 'nearest',
             'intersect' => true,
           ],
           'scales' => [
             'xAxes' => [[
               'display' => true,
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => 'Count',
               ]
             ]],
             'yAxes' => [[
               'display' => true,
               'scaleLabel' => [
                 'display' => true,
                 'LabelString' => 'Poll Option',
               ]
             ]]
           ]
         ]);
    return $schart;

  }
}
