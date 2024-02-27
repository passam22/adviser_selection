<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Adviser;
use App\Models\Group;
use App\Models\Member;
use App\Models\Application;
use App\Mail\RequestMail;
use Mail;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) 
        {
            if(Auth::user()->account_type!="GROUP")
              return redirect('/dashboard');
            else return $next($request);            
        });       
    }

    public function select_adviser()
    {
        $myid = Auth::user()->id;

        $advisers = Adviser::whereRaw("id not in (select adviser_id from applications where group_id=$myid)")->get();

        $info = Group::where('id', $myid)->first();
        if($info['group_status']!="NO ADVISER") $advisers = array();
       
        $data = array(
            'menu' => "Select Adviser",
            'advisers' => $advisers,
            'info' => $info
        );

        return view("groups.select_adviser", $data);
    }

    public function request_adviser($adviserid)
    {
        $myid = Auth::user()->id;

        //CHECK IF YOU ALREADY HAVE an APPROVED ADVISER
        $ginfo = Group::where('id', $myid)->first();
        if($ginfo['group_status']!="NO ADVISER") return redirect('/select_adviser')->with("error", "Can't process request. You already have an approved adviser.");

        //CHECK IF ADVISER HAS 3 GROUPS ALREADY
        $info = Adviser::where('id', $adviserid)->first();
        if($info['no_selected']>=3) return redirect('/select_adviser')->with("error", "Can't process request. Adviser limit is reached.");

        //CHECK IF YOU HAVE ALREADY REQUESTED FOR THIS ADVISER
        $ctr = Application::where('adviser_id', $adviserid)->where('group_id', $myid)->count();
        if($ctr>0) return redirect('/select_adviser')->with("error", "Can't process request. You have already requested for this adviser.");

        $record = array(
            'adviser_id' => $adviserid,
            'group_id' => $myid,
            'date_applied' => date("Y-m-d H:i:s"),
            'application_status' => 'PENDING',            
        );
        Application::create($record);

        //SEND MAIL to ADVISER
        $mailData = array(            
            'group_name' => $ginfo['group_name'],           
            'members' => Member::where("group_id", $myid)->get(),
        );
        Mail::to($info['email'])->send(new RequestMail($mailData));


        return redirect('/select_adviser')->with("success", "Request successfully sent.");
    }

    public function cancel_request($adviserid)
    {
        $myid = Auth::user()->id;

        //CHECK IF PENDING
        $ctr = Application::where('adviser_id', $adviserid)->where('group_id', $myid)->where("application_status", "PENDING")->count();
        if($ctr==0) return redirect('/requests_sent')->with("error", "Can't process request. Application has been approved/rejected.");

        Application::where('adviser_id', $adviserid)->where('group_id', $myid)->where("application_status", "PENDING")->delete();

        return redirect('/requests_sent')->with("success", "Request successfully cancelled.");
    }

    public function requests_sent()
    {
        $myid = Auth::user()->id;

        $advisers = Application::where("group_id", $myid)            
            ->leftJoin('advisers', 'applications.adviser_id', '=', 'advisers.id')
            ->get();

        $info = Group::where('id', $myid)->first();        
       
        $data = array(
            'menu' => "Requests Sent",
            'advisers' => $advisers,
            'info' => $info
        );

        return view("groups.requests_sent", $data);
    }
}
