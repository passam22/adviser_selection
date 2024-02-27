<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Adviser;
use App\Models\Group;
use App\Models\Member;
use App\Models\Application;
use App\Mail\ApproveMail;
use App\Mail\RejectedMail;
use Mail;

class AdviserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) 
        {
            if(Auth::user()->account_type!="ADVISER")
              return redirect('/dashboard');  
            else return $next($request);          
        });       
    }

    public function application_list()
    {
        $myid = Auth::user()->id;

        $requests = Application::where("adviser_id", $myid)
            ->where("application_status", 'PENDING')
            ->leftJoin('groups', 'applications.group_id', '=', 'groups.id')
            ->get();

        foreach($requests as $key => $item)
        {
            $requests[$key]['members'] =  Member::where("group_id", $item['id'])->get();
        }

        $data = array(
            'menu' => 'Pending Requests',
            'requests' => $requests,
            'info' => Adviser::where('id', $myid)->first(),
        );

        return view("advisers.application_list", $data);
    }

    public function approve_group($groupid)
    {
        $myid = Auth::user()->id;

        //CHECK IF GROUP ALREADY HAVE an APPROVED ADVISER
        $info = Group::where('id', $groupid)->first();
        if($info['group_status']!="NO ADVISER") return redirect('/application_list')->with("error", "Can't process transaction. The group has already an approved adviser.");

        //CHECK IF ADVISER HAS 3 GROUPS ALREADY
        $info = Adviser::where('id', $myid)->first();
        if($info['no_selected']>=3) return redirect('/application_list')->with("error", "Can't process transaction. Adviser limit is reached.");
        
        //UPDATE APPLICATIONS TABLE
        $record = array(           
            'application_status' => 'APPROVED',           
            'date_approved' => date("Y-m-d H:i:s"),    
        );
        Application::where('adviser_id', $myid)->where('group_id', $groupid)->update($record);
        
        //UPDATE GROUPS ADVISER
        $record = array(           
            'group_status' => "WITH ADVISER",  
            'approved_adviser' => $myid,              
        );
        Group::where('id', $groupid)->update($record);

        //UPDATE ADVISERS COUNT OF APPROVED GROUPS
        $ctr = Group::where('approved_adviser', $myid)->count();
        $record = array(                     
            'no_selected' => $ctr,              
        );
        Adviser::where('id', $myid)->update($record);

        //ADVISER - CANCEL OTHER APPLICATIONS - LIMIT REACHED
        if($ctr==3)
        {
            $record = array(           
                'application_status' => 'REJECTED',  
                'reason' => 'LIMIT REACHED',        
                'date_rejected' => date("Y-m-d H:i:s"),    
            );
            Application::where('application_status', 'PENDING')->where('adviser_id', $myid)->update($record);

            
        }
       
        //GROUP - CANCEL ALL REQUESTS - WITH APPROVED ADVISER
        $record = array(           
            'application_status' => 'CANCELLED',  
            'reason' => 'WITH APPROVED ADVISER',        
            'date_cancelled' => date("Y-m-d H:i:s"),    
        );
        Application::where('application_status', 'PENDING')->where('group_id', $groupid)->update($record);

        //SEND MAIL to STUDENTS
        $members = Member::where("group_id", $groupid)->get();
        foreach($members as $item)
        {
            $mailData = array(            
                'adviser_name' => $info['adviser_name'],                           
            );
            Mail::to($item->email)->send(new ApproveMail($mailData));
        }

        //SEND MAIL to STUDENTS  - REJECTED
        $groups = Application::where('application_status', 'REJECTED')->where('reason', 'LIMIT REACHED')->where("adviser_id", $myid)->get();
        foreach($groups as $group)
        {
            $members = Member::where("group_id", $group->group_id)->get();
            foreach($members as $item)
            {
                $mailData = array(            
                    'adviser_name' => $info['adviser_name'],                           
                );
                Mail::to($item->email)->send(new RejectedMail($mailData));
            }
        }
        
        

        return redirect('/application_list')->with("success", "Group successfully approved.");
    }

    public function selected_groups()
    {
        $myid = Auth::user()->id;

        $groups = Application::where("adviser_id", $myid)
            ->where("application_status", 'APPROVED')
            ->leftJoin('groups', 'applications.group_id', '=', 'groups.id')
            ->get();

        foreach($groups as $key => $item)
        {
            $groups[$key]['members'] =  Member::where("group_id", $item['id'])->get();
        }

        $data = array(
            'menu' => "Selected Groups",
            'groups' => $groups,
        );

        return view("advisers.selected_groups", $data);
    }

    public function reject_group($groupid, Request $request)
    {
        $myid = Auth::user()->id;

        //UPDATE APPLICATIONS TABLE
        $record = array(           
            'application_status' => 'REJECTED',       
            'reason' => $request->input("reason"),    
            'date_rejected' => date("Y-m-d H:i:s"),    
        );
        Application::where('adviser_id', $myid)->where('group_id', $groupid)->update($record);        

        return redirect('/application_list')->with("success", "Group successfully rejected.");
    }
}
