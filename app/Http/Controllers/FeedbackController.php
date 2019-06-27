<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\User;
use Illuminate\Http\Request;

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

      $feedbacklist = Feedback::where('status', $atype)->get();

      // add name to the results
      foreach($feedbacklist as $afb){
        if($afb->staff_id == 0){
          $afb->name = 'Anonymous';
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
      $fb = Feedback::find($request->id);
      $fb->status = 0;
      $fb->save();

      return redirect(route('feedback.list', [], false));
    }

}
