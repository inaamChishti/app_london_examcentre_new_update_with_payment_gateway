<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom right, #0056b3, #ff7f00);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-wrapper {
            background-color: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            text-align: center;
        }

        .login-wrapper h1 {
            font-size: 24px;
            color: #0056b3;
            margin-bottom: 20px;
        }

        .login-wrapper img.logo {
            width: auto;
            height: 120px; /* Increased height for larger logos */
            max-width: 300px; /* Constrain width to prevent overflow */
            margin: 10px auto;
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #0056b3;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #ff7f00;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #cc6600;
        }

        .footer {
            margin-top: 20px;
            font-size: 13px;
            color: #666;
        }

        .login-header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap; /* Allow wrapping to prevent overflow */
        }

        .login-header img {
            height: 120px; /* Increased height for larger logos */
            max-width: 300px; /* Constrain width to prevent overflow */
            object-fit: contain; /* Maintain aspect ratio */
        }
        .alert-danger{
            color: red;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-header">
            <img src="{{ asset('images/LondonExamCentre.png') }}" alt="London Exam Centre Logo" class="logo">
        </div>
        <h1>Login to London Exam Centre</h1>
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Enhanced Error Message -->
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ url('custom-login') }}" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="email" value="{{ old('email') }}" required placeholder=" ">

                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <input type="password" name="password" required placeholder=" ">
                <label>Password</label>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <input type="submit" value="Login">
        </form>
        <div class="footer">
            © 2025 London Exam Centre. All rights reserved.
        </div>
    </div>
</body>

</html>
