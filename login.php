<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SecuroGuard - Login</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }

      body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      }

      .login-container {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
      }

      .logo {
        text-align: center;
        margin-bottom: 2rem;
      }

      .logo h1 {
        color: #764ba2;
        font-size: 2rem;
        margin-bottom: 0.5rem;
      }

      .form-group {
        margin-bottom: 1.5rem;
      }

      .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #4a5568;
      }

      .form-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 1rem;
        transition: border-color 0.2s;
      }

      .form-group input:focus {
        outline: none;
        border-color: #764ba2;
      }

      .forgot-password {
        text-align: right;
        margin-bottom: 1rem;
      }

      .forgot-password a {
        color: #764ba2;
        text-decoration: none;
        font-size: 0.9rem;
      }

      .login-btn {
        width: 100%;
        padding: 0.75rem;
        background: #764ba2;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
      }

      .login-btn:hover {
        background: #663c91;
      }

      .google-login {
        width: 100%;
        padding: 0.75rem;
        background: white;
        color: #4a5568;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
      }

      .signup-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #4a5568;
      }

      .signup-link a {
        color: #764ba2;
        text-decoration: none;
      }

      .spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid #ffffff;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 0.5rem;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }

      .error-message {
        color: #e53e3e;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        display: none;
      }
    </style>
  </head>
  <body>
    <div class="login-container">
      <div class="logo">
       <h1><a href="index.php">SecuroGuard</a></h1>
        <p>Fake Review Detection</p>
      </div>
      <form action="login_handler.php" method="POST">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" required />
          <div class="error-message" id="emailError">
            Please enter a valid email address
          </div>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" required />
          <div class="error-message" id="passwordError">
            Password is required
          </div>
        </div>
        <div class="forgot-password">
          <a href="#">Forgot Password?</a>
        </div>
        <button type="submit" class="login-btn" id="loginBtn">
          Log in
          <span class="spinner" id="loginSpinner"></span>
        </button>
        <a href="google_login.php">
          <button type="button" class="google-signup">
            <img src="images.jfif" alt="Google" style="width: 20px; height: 20px;">
            Continue with Google
          </button>
        </a>
      </form>
      <div class="signup-link">
        Don't have an account? <a href="signup.php">Sign up here</a>
      </div>
    </div>

    <script>
      document
        .getElementById("loginForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const email = document.getElementById("email");
          const password = document.getElementById("password");
          const emailError = document.getElementById("emailError");
          const passwordError = document.getElementById("passwordError");
          const loginBtn = document.getElementById("loginBtn");
          const spinner = document.getElementById("loginSpinner");

          // Reset errors
          emailError.style.display = "none";
          passwordError.style.display = "none";

          // Validate
          let isValid = true;

          if (!email.value || !email.value.includes("@")) {
            emailError.style.display = "block";
            isValid = false;
          }

          if (!password.value) {
            passwordError.style.display = "block";
            isValid = false;
          }

          if (isValid) {
            // Show loading state
            spinner.style.display = "inline-block";
            loginBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
              // Hide loading state
              spinner.style.display = "none";
              loginBtn.disabled = false;

              // Redirect to home page (in a real app)
              alert("Login successful! Redirecting to home page...");
            }, 2000);
          }
        });
    </script>
  </body>
</html>
