@extends("advisers.template",['menu' => $menu])

@section("content")
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">List of Request Applications</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Pending Applications</li>
        </ol>
        <div class="row">
            <div class="col-sm-12">
                @if($info->no_selected<3)
                    <div class="alert alert-info alert-dismissible fade show" role="alert">                                
                        <strong>FYI!</strong> You may select a maximum of three groups. Once the three groups have been selected, any remaining applications will be labeled as "<span class="text-danger">Rejected - Limit Reached</span>".
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>                    
                @else
                    <div class="alert alert-info alert-dismissible fade show" role="alert">                                
                        <strong>FYI!</strong> You can no longer accept requests. You can only accept a maximum of three groups.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>     
                @endif

                

                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">                                
                        <strong>Ooops!</strong> {{ Session::get('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">                                
                        <strong>Success!</strong> {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            @foreach($requests as $item)
                <div class="col-xl-3 col-md-6">
                    <div class="card mb-4">                                   
                        <h5 class="card-header">
                            {{ strtoupper($item->group_name) }}                                                                                                                                       
                        </h5>
                        <ul class="list-group list-group-flush">
                            @foreach($item->members as $member)
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between small">
                                        <span><i class="fa fa-user me-2"></i> {{ $member->member_name }}</span> <span class="badge bg-light text-dark">{{ $member->role }}</span>
                                    </div>
                                </li>
                            @endforeach                           
                        </ul>
                        <div class="card-body">                          
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approvemodal" onclick="document.getElementById('approve_link').href='{{ url("/approve_group/".$item->id) }}'">
                                <i class="fas fa-thumbs-up"></i> Approve
                            </button>

                            <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#rejectmodal" onclick="document.getElementById('reject_link').action='{{ url("/reject_group/".$item->id) }}'">
                                <i class="fas fa-thumbs-down"></i> Reject
                            </button>                        
                        </div>

                        <div class="card-footer d-flex align-items-center">
                            Requested last {{ date("M d, h:i A", strtotime($item->date_applied)) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="modal" id="rejectmodal">
            <div class="modal-dialog">
              <div class="modal-content">
                        
                <div class="modal-header bg-danger text-white">
                  <h4 class="modal-title">Reject Application</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>                      
                <div class="modal-body">
                    <form method="post" action="" id="reject_link">
                        @csrf
                        <label class="form-label">Enter Reason:</label>
                        <textarea rows="5" class="form-control text-uppercase" name="reason"></textarea>
                    </form>
                </div>
                         
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" form="reject_link" value="Reject" />
                   <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                </div>
          
              </div>
            </div>
        </div>    
        
        <div class="modal" id="approvemodal">
            <div class="modal-dialog">
              <div class="modal-content">
                        
                <div class="modal-header bg-primary text-white">
                  <h4 class="modal-title">Approve Application</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>                      
                <div class="modal-body">
                    <h2>Are you sure?</h2>
                </div>
                         
                <div class="modal-footer">
                   <a href="#" id="approve_link" class="btn btn-success">Yes, Approve</a>
                   <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                </div>
          
              </div>
            </div>
        </div>    
    </div>
</main>

@endsection