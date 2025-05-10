<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Modern Design</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .login-container {
            width: 400px;
            max-width: 95%;
            padding: 40px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #777;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color:rgb(244, 128, 51);
            box-shadow: 0 0 0 3px rgba(231, 161, 92, 0.2);
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-footer a {
            color:rgb(231, 166, 92);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
        }

        .checkbox-container input {
            margin-right: 8px;
        }

        .checkbox-container label {
            font-size: 14px;
            color: #555;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 50px;
            background-color:rgb(231, 157, 92);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .btn:hover {
            background-color:rgb(192, 138, 73);
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgb(231, 166, 92);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: 0 3px 8px rgba(231, 175, 92, 0.85);
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .signup-link a {
            color:rgb(231, 178, 92);
            text-decoration: none;
            font-weight: 500;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Please login to your account</p>
        </div>

        <form name="loginForm" action="signin.php" method="post" onsubmit="return validateLogin()">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                <div id="email-error" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                <div id="password-error" class="error-message"></div>
            </div>

            

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="signup-link">
            Don't have an account? <a href="logup.php">Sign up</a>
        </div>
    </div>

    <script>
        function validateLogin() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            let isValid = true;

            // Reset error messages
            emailError.style.display = "none";
            passwordError.style.display = "none";

            // Email validation
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email)) {
                emailError.textContent = "Please enter a valid email address.";
                emailError.style.display = "block";
                isValid = false;
            }

            // Password validation
            if (password === "") {
                passwordError.textContent = "Password field cannot be empty.";
                passwordError.style.display = "block";
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>
</html>