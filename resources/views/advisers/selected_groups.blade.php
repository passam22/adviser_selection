@extends("advisers.template",['menu' => $menu])

@section("content")
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">List of Approved Applications</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Chosen Groups</li>
        </ol>
        <div class="row">
            <div class="col-sm-12">                

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
            @foreach($groups as $item)
                <div class="col-xl-3 col-md-6">
                    <div class="card mb-4">                                   
                        <h5 class="card-header bg-light-success">
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

                        <div class="card-footer d-flex align-items-center">
                            Approved last {{ date("M d, h:i A", strtotime($item->date_approved)) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        
    </div>
</main>

@endsection