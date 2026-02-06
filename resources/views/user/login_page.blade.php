<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dark-shadow {
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.25),
                0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
</head>

<body class="bg-white d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center w-100">
            <div class="col-md-5 col-lg-4">
                <div class="card dark-shadow border-0">
                    <div class="card-header text-center fw-bold text-white"
                        style="background: linear-gradient(135deg, #3556eb, #120d52);">
                        Login
                    </div>

                    <div class="error mt-3 ms-3 text-danger">

                    </div>

                    <div class="card-body">

                        <form id="loginForm">
                            @csrf
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                                <span class="text-danger" id="email_error"></span>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control">
                                <span class="text-danger" id="password_error"></span>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3 loginBtn">Login</button>
                        </form>
                    </div>

                    <div class="card-footer text-center">
                        <a href="{{ route('register') }}">Create new account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $("#loginForm").submit(function(e) {
            e.preventDefault();
            $('#email_error').text('');
            $('#password_error').text('');
            let email = $('#email').val();
            let password = $('#password').val();
            if (!email) {
                $('#email_error').text('Email Required');
                return false;
            }

            if (!password) {
                $('#password_error').text('Password Required');
                return false;
            }
            $.ajax({
                url: "{{ route('loginRedirect') }}",
                type: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.status){
                        window.location.href = response.redirect;
                    }else{
                        $('.error').html(response.message);
                    }
                }
            });
        });

    </script>
</body>

</html>
