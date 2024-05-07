<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/favicon.png" type="image/png" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <title>Signup</title>
</head>
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


<body class="bg-img-container bg-img-container-register" style="background-image: url('frontend_assets/jobs/n.jpg'); background-repeat: no-repeat;background-size: cover;">
    <!--wrapper-->
    <div class="wrapper">
        <div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                    <div class="col mx-auto">
                        <div class="my-4 text-center">
                            <img src="assets/images/pnex-icon.png" width="180" alt="" />
                        </div>
                        <div class="card bg-transparent blured-card blured-card-register fade-in-card" >
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Sign Up</h3>
                                        <p>Already have an account? <a href="{{ url('login') }}" style="color: #ff0027; ">Sign in here</a>
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
                                    <div class=" text-center mb-4"> <span> SIGN UP WITH EMAIL</span>
                                        <hr/>
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3 needs-validation" novalidate method="POST" action="{{ url('register') }}">
                                            @csrf
                                            <div class="col-sm-12">
                                                <label for="name" class="form-label">Full Name *</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                                    placeholder="Bilal Khan" required>
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please Enter Your Name</div>
                                            </div>

                                            <div class="col-12">
                                                <label for="email" class="form-label">Email Address
                                                    *</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                                                    placeholder="example@user.com" required>
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please Enter a Valid Email Address</div>
                                            </div>
                                            <div class="col-12">
                                                <label for="password" class="form-label">Password *</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0"
                                                        id="password" name="password" placeholder="Enter Password"
                                                        required minlength="5">
                                                    <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                            class='bx bx-hide'></i></a>
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Password must be 5 characters long
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="phone_no" class="form-label">Phone No
                                                    *</label>
                                                <input type="number" class="form-control" id="phone_no"
                                                    name="phone_no" placeholder="Phone No" required minlength="11" value="{{ old('phone_no') }}">
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Phone No must be 11 characters long</div>
                                            </div>


                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary" style="background-color: #ff0027; "><i
                                                            class='bx bx-user'></i>Sign up</button>
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
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--Password show & hide js -->
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
    <!--app JS-->
    <script src="assets/js/app.js"></script>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
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
            $("#dismiss").delay(3000).slideUp(300, function() {
                $(this).alert('close');
            });
        });
    </script>
</body>

</html>
