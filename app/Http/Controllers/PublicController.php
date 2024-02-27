<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adviser;
use App\Models\Group;
use App\Models\Member;
use App\Models\Application;

class PublicController extends Controller
{
    public function list_of_groups()
    {      
        $groups = Group::orderBy('group_name')
            ->select("groups.*", "advisers.*", "groups.id as group_id")
            ->leftJoin('advisers', 'groups.approved_adviser', '=', 'advisers.id')
            ->get();   
            
        foreach($groups as $key => $item)
        {
            $groups[$key]['members'] =  Member::where("group_id", $item['group_id'])->get();
            $groups[$key]['no_sent'] =  Application::where("group_id", $item['group_id'])->count();
        }
       
        $data = array(
            'menu' => "List of Groups",
            'groups' => $groups,            
        );

        return view("list_of_groups", $data);
    }
}
