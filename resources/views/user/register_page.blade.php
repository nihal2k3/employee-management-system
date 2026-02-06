<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card border-0 dark-shadow">
                    <div class="card-header text-center fw-bold text-white"
                        style="background: linear-gradient(135deg, #3556eb, #120d52);">
                        Register
                    </div>

                    <div class="error mt-2">

                    </div>
                    <div class="card-body">
                        <form id="registerForm">
                            @csrf

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                                <span class="text-danger" id="name_error"></span>
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                                <span class="text-danger" id="email_error"></span>
                            </div>

                            <div class="mb-3 position-relative">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control">
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                                    style="cursor: pointer; padding-top:25px;" onclick="togglePassword()">
                                    👁️
                                </span>
                                <span class="text-danger" id="password_error"></span>
                            </div>

                            <div class="mb-3 position-relative">
                                <label>Confirm Password</label>
                                <input type="password" name="password_confirmation" id="cp" class="form-control">
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                                    style="cursor: pointer; padding-top:25px;" onclick="toggleConfirmPassword()">
                                    👁️
                                </span>
                                <span class="text-danger" id="cp_error"></span>

                            </div>

                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>

                    <div class="card-footer text-center">
                        <a href="{{ route('login') }}">Already have an account?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            let password = document.getElementById("password");
            password.type = password.type === "password" ? "text" : "password";
        }

        function toggleConfirmPassword() {
            let cp = document.getElementById("cp");
            cp.type = cp.type === "password" ? "text" : "password";
        }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $("#registerForm").submit(function(e) {
            e.preventDefault();
            $('#email_error').text('');
            $('#password_error').text('');
            $('#name_error').text('');
            $('#cp_error').text('');
            let email = $('#email').val();
            let password = $('#password').val();

            if (!$('#name').val()) {
                $('#name_error').text('Name Required');
                return false;
            }

            if (!email) {
                $('#email_error').text('Email Required');
                return false;
            }

            if (!password) {
                $('#password_error').text('Password Required');
                return false;
            }

            if (!$('#cp').val()) {
                $('#cp_error').text('Confirm Password Required');
                return false;
            }

            if (password !== $('#cp').val()) {
                $('#cp_error').text('Password Do not Match');
                return false;
            }
            $.ajax({
                url: "{{ route('saveregistration') }}",
                type: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        window.location.href = response.redirect;
                    } else {
                        $('.error').html(response.message);
                    }
                }
            });
        });
    </script>
</body>

</html>
