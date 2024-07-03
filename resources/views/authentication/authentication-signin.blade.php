<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->

    <!-- <link rel="icon" href="assets/favicon.png" type="image/png" /> -->
    <link rel="icon" href="assets/images/logo-img.png" type="image/png" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">

    <style>
        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
    margin-top: 0;
    margin-bottom: 0.5rem;
    font-weight: 500;
    line-height: 1.2;
    color: #fff;
}

element.style {
}
.form-label {
    margin-bottom: 0.5rem;
}
label {
    display: inline-block;
}
*, ::after, ::before {
    box-sizing: border-box;
}
user agent stylesheet
label {
    cursor: default;
}
.g-3, .gy-3 {
    --bs-gutter-y: 1rem;
}
.g-3, .gx-3 {
    --bs-gutter-x: 1rem;
}
.row {
    --bs-gutter-x: 1.5rem;
    --bs-gutter-y: 0;
    display: flex;
    flex-wrap: wrap;
    margin-top: calc(var(--bs-gutter-y) * -1);
    margin-right: calc(var(--bs-gutter-x)/ -2);
    margin-left: calc(var(--bs-gutter-x)/ -2);
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid transparent;
    border-radius: 0.25rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
}
body {
    font-size: 14px;
    color: #fff;
    letter-spacing: .5px;
    background: #f7f7ff;
    overflow-x: hidden;
    font-family: Roboto, sans-serif;
}
.login-separater span {
    position: relative;
    top: 26px;
    margin-top: -10px;
    background: #fff;
    padding: 5px;
    font-size: 12px;
    color: #ff0027;
    z-index: 1;
}
.input-group-text {
    display: flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #ff0027;
    text-align: center;
    white-space: nowrap;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.input-group-text {
    display: flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: center;
    white-space: nowrap;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.input-group-text {
    display: flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #ff0027;
    text-align: center;
    white-space: nowrap;
    background-color: #037de2;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
        </style>
 
    <title>Sign in</title>
</head>

<body class="bg-img-container bg-img-container-register" style="background-image: url('frontend_assets/jobs/n.jpg'); background-repeat: no-repeat;background-size: cover;">
    <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            <img src="assets/images/pnex-icon.png" width="120" alt="" />
                        </div>

                    
                         


                        <div class="card bg-transparent blured-card blured-card-register fade-in-card" >
                       
                            <div class="card-body"> 
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Sign in</h3>
                                        <p style="color: #ff0027">Taghreed Mohammed Ibrahim Al Maqil Trading Est </a>
                                        </p>
                                    </div>
                                   @if (Session::has('status'))
                                        <div class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show" id="dismiss">
                                            <div class="text-white">{{ Session::get('message')}}</div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                                {{ Session::forget('status') }}
                                                {{ Session::forget('message') }}
                                        </div>
                                    @endif
                                    <div class=" text-center mb-4"> <span> SIGN IN WITH USERNAME</span>
                                        <hr />
                                    </div>
                                    <div class="form-body">
                                        <form method="post" action="{{ url('adminlogin') }}"class="row g-3 needs-validation" novalidate >
                                            @csrf
                                            <div class="col-12">
                                                <label for="email" class="form-label">Email *</label>
                                                <input type="text" class="form-control" id="email" name="email"
                                                    placeholder="email" required>
                                                    <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback" id="username-msg">Please Enter email</div>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter
                                                    Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0"
                                                        id="password" required name="password"
                                                        placeholder="Enter Password" minlength="5"> <a href="javascript:;"
                                                        class="input-group-text bg-transparent"><i
                                                            class='bx bx-hide'></i></a>
                                                            <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback" id="password-msg">Password must be 5 characters long</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {{-- <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckChecked" checked>
                                                    <label class="form-check-label"
                                                        for="">Remember Me</label>
                                                </div> --}}
                                            </div>
                                            <!-- <div class="col-md-6 text-end"> <a
                                                    href="{{ url('authentication-forgot-password') }}" style="color: #ff0027">Forgot Password
                                                    ?</a>
                                            </div> -->
                                            <div class="col-12 mt-3">
                                                <div class="d-grid">
                                                    <button id="login-button" type="submit" disabled class="btn text-white" style="background-color: #ff0027; "><i
                                                            class="bx bxs-lock-open"></i>Sign in</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->

    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    	<script>
            // Example starter JavaScript for disabling form submissions if there are invalid fields
                (function () {
                  'use strict'

                  // Fetch all the forms we want to apply custom Bootstrap validation styles to
                  var forms = document.querySelectorAll('.needs-validation')

                  // Loop over them and prevent submission
                  Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                      form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                          event.preventDefault()
                          event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                      }, false)
                    })
                })()
        </script>

        <script>
            $(document).ready(function() {
                $("#login-button").attr('disabled', false);
                $("#dismiss").delay(3000).slideUp(300, function() {
                    $(this).alert('close');
                });
            });
        </script>
</body>

</html>
