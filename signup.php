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
    <title>SecuroGuard - Sign Up</title>
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
        padding: 1rem;
      }

      .signup-container {
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

      .password-strength {
        margin-top: 0.5rem;
        font-size: 0.9rem;
      }

      .strength-meter {
        height: 4px;
        background: #e2e8f0;
        margin-top: 0.5rem;
        border-radius: 2px;
        overflow: hidden;
      }

      .strength-meter div {
        height: 100%;
        width: 0%;
        transition: width 0.3s, background 0.3s;
      }

      .checkbox-group {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
      }

      .checkbox-group input {
        margin-top: 0.25rem;
      }

      .checkbox-group label {
        color: #4a5568;
        font-size: 0.9rem;
      }

      .checkbox-group a {
        color: #764ba2;
        text-decoration: none;
      }

      .signup-btn {
        width: 100%;
        padding: 0.75rem;
        background: #764ba2;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .signup-btn:hover {
        background: #663c91;
      }

      .signup-btn:disabled {
        background: #a0aec0;
        cursor: not-allowed;
      }

      .google-signup {
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

      .login-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #4a5568;
      }

      .login-link a {
        color: #764ba2;
        text-decoration: none;
      }

      .error-message {
        color: #e53e3e;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        display: none;
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
    </style>
  </head>
  <body>
    <div class="signup-container">
      <div class="logo">
      <h1><a onclick="window.location.href='index.php'" href="#">SecuroGuard</a></h1>
      <p>Create your account</p>
      </div>
      <form action="signup_handler.php" method="POST">

        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" name="name" required />
          <div class="error-message" id="nameError">Please enter your name</div>
        </div>
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
          <div class="password-strength">
            <span id="strengthText">Password strength: </span>
            <div class="strength-meter">
              <div id="strengthIndicator"></div>
            </div>
          </div>
          <div class="error-message" id="passwordError">
            Password must be at least 8 characters
          </div>
        </div>
        <div class="checkbox-group">
          <input type="checkbox" id="privacy" required />
          <label for="privacy">
            I agree to the <a href="#">Privacy Policy</a> and
            <a href="#">Terms of Service</a>
          </label>
        </div>
        <button type="submit" class="signup-btn" id="signupBtn">
          Sign up
          <span class="spinner" id="signupSpinner"></span>
        </button>
        <button type="button" class="google-signup">
          <img
            src="images.jfif"
            alt="Google"
            style="width: 20px; height: 20px;"
          />
          Continue with Google
        </button>
      </form>
      <div class="login-link">
        Already have an account? <a href="login.php">Log in</a>
      </div>
    </div>

    <script>
      const password = document.getElementById("password");
      const strengthIndicator = document.getElementById("strengthIndicator");
      const strengthText = document.getElementById("strengthText");

      function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength += 25;
        if (password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[0-9]/)) strength += 25;
        if (password.match(/[^A-Za-z0-9]/)) strength += 25;
        return strength;
      }


      //check this later
      password.addEventListener("input", function () {
        const strength = checkPasswordStrength(this.value);
        strengthIndicator.style.width = strength + "%";

        if (strength <= 25) {
          strengthIndicator.style.background = "#f56565";
          strengthText.textContent = "Password strength: Weak";
        } else if (strength <= 50) {
          strengthIndicator.style.background = "#ed8936";
          strengthText.textContent = "Password strength: Fair";
        } else if (strength <= 75) {
          strengthIndicator.style.background = "#48bb78";
          strengthText.textContent = "Password strength: Good";
        } else {
          strengthIndicator.style.background = "#2f855a";
          strengthText.textContent = "Password strength: Strong";
        }
      });


      document
        .getElementById("signupForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const name = document.getElementById("name");
          const email = document.getElementById("email");
          const privacy = document.getElementById("privacy");
          const nameError = document.getElementById("nameError");
          const emailError = document.getElementById("emailError");
          const passwordError = document.getElementById("passwordError");
          const signupBtn = document.getElementById("signupBtn");
          const spinner = document.getElementById("signupSpinner");

          // Reset errors
          nameError.style.display = "none";
          emailError.style.display = "none";
          passwordError.style.display = "none";

          // Validate
          let isValid = true;

          if (!name.value) {
            nameError.style.display = "block";
            isValid = false;
          }

          if (!email.value || !email.value.includes("@")) {
            emailError.style.display = "block";
            isValid = false;
          }

          if (password.value.length < 8) {
            passwordError.style.display = "block";
            isValid = false;
          }

          if (!privacy.checked) {
            isValid = false;
            alert("Please agree to the Privacy Policy and Terms of Service");
          }

          if (isValid) {
            // Show loading state
            spinner.style.display = "inline-block";
            signupBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
              // Hide loading state
              spinner.style.display = "none";
              signupBtn.disabled = false;

              // Show success message
              alert(
                "Account created successfully! Redirecting to home page..."
              );
            }, 2000);
          }
        });
    </script>
  </body>
</html>
