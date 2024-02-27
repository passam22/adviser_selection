@extends(Auth::user()->account_type=="GROUP" ? "groups.template" : "advisers.template",['menu' => $menu])


@section("content")
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">List of Groups</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Capstone Project 1</li>
        </ol>
        
        
        <div class="card2 mb-4">
            
            <div class="card2-body">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-success">
                            <th class="text-center">Group</th>
                            <th class="text-center">Members</th>
                            <th class="text-center">No. of Requests Sent</th>
                            <th class="text-center">Project Adviser</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $item)
                            <tr class="{{ Auth::user()->account_type=="GROUP" && $item->group_id==Auth::user()->id ? 'table-primary' : '' }} {{ Auth::user()->account_type=="ADVISER" && $item->approved_adviser==Auth::user()->id ? 'table-primary' : '' }}">
                                <td><i class="fa fa-user-circle"></i>  {{ $item->group_name }}</td>
                                <td>
                                    <ul>
                                        @foreach($item->members as $member)
                                            <li>{{ ucwords(strtolower($member->role)) }} - {{ $member->member_name }} </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center"> 
                                    @if($item->no_sent==0)
                                    <span class=" text-danger">no attempts</span>
                                    @else
                                        <span class="fw-bold text-success">{{ $item->no_sent }} req/s </span>
                                    @endif
                                    
                                
                                </td>
                                <td class="text-start">
                                    @if($item->group_status=="NO ADVISER")
                                        <span class="text-danger"><i class="fa fa-exclamation-circle"></i> No Adviser Yet</span>
                                    @else
                                        <img src="{{ asset("/images/".$item->photo) }}" class="rounded-circle" width="30" />    
                                        {{ ucwords(strtolower($item->adviser_name)) }}
                                    @endif
                                   
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        

         
        
        
    </div>
</main>

@endsection