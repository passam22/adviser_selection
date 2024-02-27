@extends("groups.template",['menu' => $menu])

@section("content")
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Select a Project Adviser</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">List of Advisers</li>
        </ol>
        <div class="row">
            <div class="col-sm-12">
                @if($info->group_status=='NO ADVISER')
                    <div class="alert alert-info alert-dismissible fade show" role="alert">                                
                        <strong>FYI!</strong> You can request for more than one adviser. The first to approve your request will be your project adviser. All the other applications will be automatically cancelled.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>                        
                @else
                    <div class="alert alert-info alert-dismissible fade show" role="alert">                                
                        <strong>FYI!</strong> You can no longer request for an adviser.
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
            @foreach($advisers as $item)
                <div class="col-xl-3 col-md-6">
                    <div class="card mb-4">    
                        <img class="card-img-top" src="{{ asset("/images/".$item->photo) }}" height="350" alt="Card image">                               
                        <div class="card-body">
                            <h4 class="card-title text-center">{{ ucwords(strtolower($item->adviser_name)) }}</h4>                               
                            <div class="text-center">                                  
                                <ul class="list-inline"> 
                                    @foreach (json_decode($item->research_interests) as $interest)
                                        <li class="list-inline-item">&bull; {{ $interest }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @if($item->no_selected<3)
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#requestmodal" onclick="document.getElementById('request_link').href='{{ url("/request_adviser/".$item->id) }}'">
                                        <i class="fas fa-thumbs-up"></i> Request
                                    </button> 
                                </div> 
                            @else
                                <div class="d-grid">
                                    <button type="button" class="btn btn-secondary btn-block disabled" >
                                        <i class="fas fa-ban"></i> Limit Reached
                                    </button> 
                                </div> 
                            @endif                    
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            No. of Groups Handled: <span class="ms-2 fw-bold">{{ $item->no_selected }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

         
        
        <div class="modal" id="requestmodal">
            <div class="modal-dialog">
              <div class="modal-content">
                        
                <div class="modal-header bg-primary text-white">
                  <h4 class="modal-title">Request as Adviser</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>                      
                <div class="modal-body">
                    <h2>Are you sure?</h2>
                </div>
                         
                <div class="modal-footer">
                   <a href="#" id="request_link" class="btn btn-success">Yes, Request</a>
                   <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                </div>
          
              </div>
            </div>
        </div>    
    </div>
</main>

@endsection