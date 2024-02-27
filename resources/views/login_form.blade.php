<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Adviser Selection</title>
        <link href="{{ asset("css/styles.css") }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>                    
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="row justify-content-center">
                                    <img src="{{ asset('tlc_logo_mini.png') }}" class="col-lg-4 img mt-5" width="150" height="150"/>
                                </div>
                                <div class="card shadow-lg border-0 rounded-lg mt-4">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Capstone Adviser Matching System</h3></div>
                                    <div class="card-body">
                                        <form method="post" action="{{ url("/check_login") }}">
                                            @csrf
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputEmail" required type="text" name="username" placeholder="Username" autocomplete="off" />
                                                <label for="inputEmail">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" type="password" name="password" required placeholder="Password" />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">                                              
                                                <input type="submit" class="btn btn-primary pt-2 pb-2" value="Login to System">
                                            </div>
                                        </form>
                                    </div>
                                  
                                </div>
                                <figure class="text-center mt-4">
                                    <blockquote class="blockquote text-white">This application demonstrates a Laravel Project that incorporates CRUD functionality. Source code will be made accessible on Github after the selection process. </blockquote>
                                    <figcaption class="blockquote-footer text-white">Software Engineering 2 </figcaption></p>
                                </figure>

                                @if(Session::has('error'))
                                    <div class="alert alert-danger mt-2 alert-dismissable">
                                        <strong>Ooops!</strong> {{ Session::get('error') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset("js/scripts.js") }}"></script>
    </body>
</html>
