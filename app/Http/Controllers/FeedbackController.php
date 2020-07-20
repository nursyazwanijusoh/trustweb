<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\User;
use App\Mail\RespFeedback;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function sform()
    {
      return view('feedback.sform');
    }

    public function mobform()
    {
      return view('feedback.mobform');
    }

    public function submit(Request $request)
    {
      // dd($request);
      $staffid = 0;
      if(session()->has('staffdata')){
        $staffid = \Session::get('staffdata')['id'];
      }
      $fb = new Feedback;
      $fb->staff_id = $staffid;
      $fb->title = $request->title;
      $fb->content = $request->content;
      $fb->agent = $request->header('user-agent');

      if($request->filled('ctc')){
        $fb->contact = $request->ctc;
      } else {
        $fb->contact = 'no';
      }

      $fb->status = 1;
      $fb->save();

      if($request->sos == 'web'){
        return view('feedback.sform', ['alert' => 'success']);
      } else {
        return view('feedback.mobform', ['alert' => 'success']);
      }

    }

    public function list(Request $req)
    {
      $type = 'active';
      $atype = 1;

      if($req->filled('type')){
        $type = $req->type;
      }

      if($type == 'closed'){
        $atype = 0;
      }

      $last2mon = new Carbon;
      $last2mon->subMonths(2);

      $feedbacklist = Feedback::where('status', $atype)
        ->whereDate('created_at', '>', $last2mon->toDateString())->get();

      // add name to the results
      foreach($feedbacklist as $afb){
        if($afb->staff_id == 0){
          if(isset($afb->contact)){
            $afb->name = $afb->contact;
          } else {
            $afb->name = 'Anonymous';
          }

          $afb->staff_no = 'Anon';
        } else {
          $user = User::find($afb->staff_id);
          $afb->name = $user->name;
          $afb->staff_no = $user->staff_no;
        }

      }

      return view('feedback.list', ['type' => $type, 'data' => $feedbacklist]);

    }

    public function close(Request $request)
    {
      $fb = Feedback::findOrFail($request->id);
      $ctc = $fb->getContact();
      $isemail = 'disabled';
      if(strpos($ctc, '@') !== false){
        $isemail = ' ';
      }

      return view('feedback.close', ['feedback' => $fb, 'isemail' => $isemail, 'contact' => $ctc]);

    }

    public function doclose(Request $req){

      // dd($req->all());

      $fb = Feedback::find($req->id);
      $fb->status = 0;
      $fb->remark = $req->remark;
      $fb->closed_by =  $req->session()->get('staffdata')['id'];
      $fb->save();

      if($req->filled('sendemail')){
        \Mail::to($fb->getContact())->send(new RespFeedback($fb));
        $fb->contacted = true;
      } else {
        $fb->contacted = false;
      }

      $fb->save();

      return redirect(route('feedback.list', [], false));
    }

}
