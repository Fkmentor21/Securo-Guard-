<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SecuroGuard - Feedback</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }

      body {
        min-height: 100vh;
        background: #f7fafc;
      }

      .navbar {
        background: white;
        padding: 1rem 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
      }

      .logo {
        color: #764ba2;
        font-size: 1.5rem;
        font-weight: bold;
        text-decoration: none;
      }

      .nav-links {
        display: flex;
        gap: 2rem;
        align-items: center;
      }

      .nav-links a {
        color: #4a5568;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
      }

      .nav-links a:hover {
        color: #764ba2;
      }

      .user-profile {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
      }

      .user-profile img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e2e8f0;
      }

      .main-content {
        max-width: 800px;
        margin: 100px auto 2rem;
        padding: 0 1rem;
      }

      .feedback-container {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .feedback-header {
        text-align: center;
        margin-bottom: 2rem;
      }

      .feedback-header h1 {
        color: #2d3748;
        margin-bottom: 1rem;
        font-size: 1.8rem;
      }

      .feedback-header p {
        color: #718096;
      }

      .rating-container {
        text-align: center;
        margin-bottom: 2rem;
      }

      .rating-container h2 {
        color: #2d3748;
        margin-bottom: 1rem;
        font-size: 1.2rem;
      }

      .stars {
        display: inline-flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
      }

      .star {
        font-size: 2rem;
        cursor: pointer;
        color: #e2e8f0;
        transition: color 0.2s;
      }

      .star.active {
        color: #fbbf24;
      }

      .feedback-form {
        max-width: 600px;
        margin: 0 auto;
      }

      .form-group {
        margin-bottom: 1.5rem;
      }

      .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #4a5568;
        font-weight: 500;
      }

      .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 1rem;
        color: #4a5568;
        background: white;
        cursor: pointer;
      }

      .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 1rem;
        min-height: 150px;
        resize: vertical;
      }

      .form-group select:focus,
      .form-group textarea:focus {
        outline: none;
        border-color: #764ba2;
      }

      .submit-btn {
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
        gap: 0.5rem;
      }

      .submit-btn:hover {
        background: #663c91;
      }

      .submit-btn:disabled {
        background: #a0aec0;
        cursor: not-allowed;
      }

      .success-message {
        display: none;
        text-align: center;
        margin-top: 2rem;
        padding: 1rem;
        background: #f0fff4;
        border: 1px solid #48bb78;
        border-radius: 5px;
        color: #2f855a;
      }

      .spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid #ffffff;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }

      @media (max-width: 768px) {
        .navbar {
          padding: 1rem;
        }

        .nav-links {
          gap: 1rem;
        }

        .feedback-container {
          padding: 1.5rem;
        }
      }

      .logout-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        padding: 10px;
        text-align: center;
      }

      .logout-menu button {
        background: #764ba2;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        width: 100%;
      }

      .logout-menu button:hover {
        background: #663c91;
      }
    </style>
  </head>
  <body>
   <nav class="navbar">
      <a href="index.php" class="logo">SecuroGuard</a>
      <div class="nav-links">
        <a href="index.php" class="active">Home</a>
        <a href="reportpage.php">Reports</a>
        <a href="feedbackpage.php">Feedback</a>
        <div class="user-profile" id="userProfile">
          <img src="images pr.png" alt="Profile" />
         <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>

          <div class="logout-menu" id="logoutMenu">
            <button id="logoutBtn">Logout</button>
          </div>
        </div>
      </div>
    </nav>

    <main class="main-content">
      <div class="feedback-container">
        <div class="feedback-header">
          <h1>Your Feedback Matters</h1>
          <p>Help us improve SecuroGuard by sharing your experience</p>
        </div>

        <form id="feedbackForm">
          <div class="rating-container">
            <h2>How would you rate your experience?</h2>
            <div class="stars" id="ratingStars">
              <span class="star" data-value="1">★</span>
              <span class="star" data-value="2">★</span>
              <span class="star" data-value="3">★</span>
              <span class="star" data-value="4">★</span>
              <span class="star" data-value="5">★</span>
            </div>
          </div>

          <div class="feedback-form">
            <div class="form-group">
              <label for="feedbackType">Feedback Type</label>
              <select id="feedbackType" required>
                <option value="">Select feedback type</option>
                <option value="suggestion">Suggestion</option>
                <option value="bug">Bug Report</option>
                <option value="accuracy">Accuracy Feedback</option>
                <option value="feature">Feature Request</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="form-group">
              <label for="feedbackText">Your Feedback</label>
              <textarea
                id="feedbackText"
                placeholder="Please share your thoughts, suggestions, or report any issues..."
                required
              ></textarea>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
              Submit Feedback
              <span class="spinner" id="submitSpinner"></span>
            </button>
          </div>
        </form>

        <div class="success-message" id="successMessage">
          Thank you for your feedback! We appreciate your help in making
          SecuroGuard better.
        </div>
      </div>
    </main>

    <script>
      // Star rating functionality
      const ratingStars = document.getElementById("ratingStars");
      const stars = ratingStars.getElementsByClassName("star");
      let currentRating = 0;

      function setRating(rating) {
        currentRating = rating;
        for (let i = 0; i < stars.length; i++) {
          stars[i].classList.toggle("active", i < rating);
        }
      }

      ratingStars.addEventListener("click", (e) => {
        if (e.target.classList.contains("star")) {
          setRating(parseInt(e.target.dataset.value));
        }
      });

      ratingStars.addEventListener("mouseover", (e) => {
        if (e.target.classList.contains("star")) {
          const rating = parseInt(e.target.dataset.value);
          for (let i = 0; i < stars.length; i++) {
            stars[i].classList.toggle("active", i < rating);
          }
        }
      });

      ratingStars.addEventListener("mouseout", () => {
        setRating(currentRating);
      });

      // Form submission
      document
        .getElementById("feedbackForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const submitBtn = document.getElementById("submitBtn");
          const spinner = document.getElementById("submitSpinner");
          const successMessage = document.getElementById("successMessage");

          if (currentRating === 0) {
            alert("Please provide a rating");
            return;
          }

          // Show loading state
          submitBtn.disabled = true;
          spinner.style.display = "inline-block";

          // Simulate API call
          setTimeout(() => {
            // Hide loading state
            spinner.style.display = "none";
            submitBtn.disabled = false;

            // Show success message
            successMessage.style.display = "block";

            // Reset form
            this.reset();
            setRating(0);

            // Hide success message after 5 seconds
            setTimeout(() => {
              successMessage.style.display = "none";
            }, 5000);
          }, 1500);
        });

      // Logout functionality
      const userProfile = document.getElementById("userProfile");
      const logoutMenu = document.getElementById("logoutMenu");
      const logoutBtn = document.getElementById("logoutBtn");

      // Toggle logout menu visibility on click
      userProfile.addEventListener("click", () => {
        logoutMenu.style.display =
          logoutMenu.style.display === "block" ? "none" : "block";
      });

      // Logout function
      logoutBtn.addEventListener("click", () => {
        window.location.href = "1-login Page.html"; // Redirect to login page
      });

      // Close menu if clicked outside
      document.addEventListener("click", (event) => {
        if (!userProfile.contains(event.target)) {
          logoutMenu.style.display = "none";
        }
      });
    </script>
  </body>
</html>
