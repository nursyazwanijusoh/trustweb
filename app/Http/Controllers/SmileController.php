<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use App\McoTravelReq;
use App\SapLeaveInfo;
use \Carbon\Carbon;
use App\User;
use DB;
use App\HappyReason;
use App\HappyType;
use GuzzleHttp\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;

class SmileController extends Controller
{
   
    
    public function __construct()
    {
        
        $this->middleware('auth');

    }
    public function index(Request $req)
    {
        return view('smile.index', []);
    }
    public function form(Request $req)
    {
        $reasons = HappyReason::where('type_id', $req->type)->get();
        $type = HappyType::where('id', $req->type)->first();

        // dd($req->type,$reasons,$type);
        return view('smile.form', ['reasons' => $reasons,'ty'=>$type]);
    }
    public function submit(Request $req)
    {
        $api_uri = env('ERA_API_URI');

        //dd($req);
        $options = [
             'json' =>[
                'type' => $req->type,
                'reason' => $req->reason,
                'remark' => $req->remark,
                'staffno'=>$req->user()->staff_no,
                'source'=>"trUSt"
            ]        
        ];
        //dd($options);
        $reclient = new Client(["base_uri" => $api_uri]);
        $request = $reclient->request('POST', '/api/happy/meter/external' .'?api_key='.env('ERA_API_KEY'), $options)->getBody()->getContents();
      
       
        //return $request;
        return view('smile.index', [ 'alert' => 'Hi. Thanks for contributing. Your input have been sent to ERA.']);
     
    }




}