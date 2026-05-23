<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} | {{ isset($page_title) ? $page_title : '' }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->
    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/core/core.css') }}">
    <!-- Layout styles -->
    @if (session()->has('selected_theme') && session()->get('selected_theme') == 'Dark')
        <link rel="stylesheet" href="{{ asset('backend/assets/css/demo2/style.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('backend/assets/css/demo1/style.min.css') }}">
    @endif
    <!-- End layout styles -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    @if (websiteSetupValue('favicon'))
        <link rel="shortcut icon" href="{{ asset('backend/admin/website_setup/' . websiteSetupValue('favicon')) }}" />
    @else
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}" />
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }

        .login-image-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 500px;
        }

        .login-image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .login-form-section {
            padding: 60px 40px;
            background: white;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            max-height: 50px;
            width: auto;
        }

        .logo-container h4 {
            color: #667eea;
            font-weight: 700;
            margin: 0;
        }

        .welcome-text {
            text-align: center;
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .form-label {
            color: #374151;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-group-text {
            background: white;
            border: 2px solid #e5e7eb;
            border-left: none;
            border-radius: 0 10px 10px 0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .input-group-text:hover {
            background: #f9fafb;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 14px 40px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .login-container {
                padding: 15px;
            }

            .login-card {
                border-radius: 15px;
            }

            .login-image-section {
                display: none;
            }

            .login-form-section {
                padding: 40px 25px;
            }

            .logo-container {
                margin-bottom: 25px;
            }

            .logo-container img {
                max-height: 40px;
            }

            .welcome-text {
                font-size: 14px;
                margin-bottom: 25px;
            }

            .form-control {
                padding: 10px 14px;
                font-size: 14px;
            }

            .btn-login {
                padding: 12px 30px;
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .login-form-section {
                padding: 30px 20px;
            }

            .form-label {
                font-size: 13px;
            }

            .form-control {
                padding: 10px 12px;
                font-size: 13px;
            }
        }

        /* Error messages */
        .invalid-feedback {
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="row g-0">
                <!-- Image Section (Hidden on Mobile) -->
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="login-image-section">
                        <img src="{{ asset('backend/log.gif') }}" alt="Login">
                    </div>
                </div>

                <!-- Form Section -->
                <div class="col-lg-6">
                    <div class="login-form-section">
                        <!-- Logo -->
                        <div class="logo-container">
                            @if (websiteSetupValue('logo'))
                                <img src="{{ asset('backend/admin/website_setup/' . websiteSetupValue('logo')) }}"
                                    alt="Logo">
                            @else
                                <h4>{{ config('app.name') }}</h4>
                            @endif
                        </div>

                        <!-- Welcome Text -->
                        <div class="welcome-text">
                            Welcome back! Log in to your account.
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('admin.login.submit') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Enter your email" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" autocomplete="current-password" name="password"
                                        placeholder="Enter your password" required>
                                    <span class="input-group-text" id="toggle-password">
                                        <i class="fa fa-eye" id="eye-icon"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/assets/vendors/core/core.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            $(document).ready(function() {
                var success_message = "{{ Session::get('success') }}";
                var error_message = "{{ Session::get('error') }}";

                if (success_message != "") {
                    success_sweet_alert(success_message);
                }
                if (error_message != "") {
                    error_sweet_alert(error_message)
                }
            });

            function success_sweet_alert(success_message) {
                Toast.fire({
                    icon: 'success',
                    title: success_message
                });
            }

            function error_sweet_alert(error_message) {
                Toast.fire({
                    icon: 'error',
                    title: error_message
                });
            }
        });

        // Toggle password visibility
        document.getElementById('toggle-password').addEventListener('click', function() {
            var passwordInput = document.getElementById('password');
            var eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>
