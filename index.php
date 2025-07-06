<?php
session_start();

// Redirect to login page if not logged in
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
    <title>SecuroGuard - Home</title>
    <style>
     
       /* ----------------------------------------
         Global Reset and Base Styles
      ---------------------------------------- */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }

      body {
        min-height: 100vh;
        background: #f7fafc;
        padding-top: 70px; /* leave space for fixed navbar */
      }

      /* ----------------------------------------
         Navbar Styling
      ---------------------------------------- */
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

      /* ----------------------------------------
         User Profile Dropdown Styling
      ---------------------------------------- */
      .user-profile {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        position: relative;
      }

      .user-profile img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e2e8f0;
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

      /* ----------------------------------------
         Main Content Area Styling
      ---------------------------------------- */
      .main-content {
        max-width: 800px;
        margin: 100px auto 2rem;
        padding: 0 1rem;
      }

      /* ----------------------------------------
         URL Input Section
      ---------------------------------------- */
      .url-section {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
      }

      .url-section h1 {
        color: #2d3748;
        margin-bottom: 1rem;
        font-size: 1.8rem;
      }

      .url-section p {
        color: #718096;
        margin-bottom: 2rem;
      }

      .url-input-group {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
      }

      .url-input {
        flex: 1;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 5px;
        font-size: 1rem;
        transition: border-color 0.2s;
      }

      .url-input:focus {
        outline: none;
        border-color: #764ba2;
      }

      .analyze-btn {
        padding: 0.75rem 1.5rem;
        background: #764ba2;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .analyze-btn:hover {
        background: #663c91;
      }

      .analyze-btn:disabled {
        background: #a0aec0;
        cursor: not-allowed;
      }

      /* ----------------------------------------
         Recent Searches Section
      ---------------------------------------- */
      .recent-searches {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .recent-searches h2 {
        color: #2d3748;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
      }

      .search-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .search-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 5px;
        transition: background 0.2s;
      }

      .search-item:hover {
        background: #f1f5f9;
      }

      .search-item-url {
        color: #4a5568;
        text-decoration: none;
        flex: 1;
        margin-right: 1rem;
      }

      .search-item-time {
        color: #718096;
        font-size: 0.9rem;
      }

      /* ----------------------------------------
         Spinner Animation for Loading
      ---------------------------------------- */
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

      /* ----------------------------------------
         Error Message Styling
      ---------------------------------------- */
      .error-message {
        color: #e53e3e;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        display: none;
      }

      /* ----------------------------------------
         Responsive Adjustments
      ---------------------------------------- */
      @media (max-width: 768px) {
        .navbar {
          padding: 1rem;
        }

        .nav-links {
          gap: 1rem;
        }

        .url-input-group {
          flex-direction: column;
        }

        .analyze-btn {
          width: 100%;
        }
      }
    </style>
  </head>
  <body>
    <!-- Navigation Bar -->
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

    <!-- Main Content -->
    <main class="main-content">
      <!-- URL Input Section -->
      <section c  lass="url-section">
        <h1>Analyze Product Reviews</h1>
        <p>Enter a product URL to analyze reviews for potential fake content</p>
        <form id="urlForm">
          <div class="url-input-group">
            <input type="url" class="url-input" id="urlInput" placeholder="Paste product URL here" required />
            <button type="submit" class="analyze-btn" id="analyzeBtn">
              Analyze <span class="spinner" id="analyzeSpinner"></span>
            </button>
          </div>
          <div class="error-message" id="urlError">Please enter a valid product URL</div>
        </form>
      </section>

      <!-- Result Modal -->
      <div id="resultModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:white; padding:2rem; box-shadow:0 4px 12px rgba(0,0,0,0.2); border-radius:10px; z-index:2000; max-width:400px;">
        <h2 style="margin-bottom: 1rem; color: #2d3748">Result</h2>
        <div id="resultLabel" style="font-size:1.2rem; font-weight:bold; margin-bottom:0.5rem;"></div>
        <div id="resultConfidence" style="margin-bottom:1rem;"></div>
        <div id="resultBadge" style="margin-bottom:1rem;"></div>
        <div id="resultTip" style="margin-bottom:1rem;"></div>
        <button onclick="window.location.href='reportpage.php'" style="padding:0.75rem 1.5rem; background:#764ba2; color:white; border:none; border-radius:5px; cursor:pointer;">View Full Analysis Report</button>
      </div>

      <!-- Recent Searches -->
      <section class="recent-searches">
        <h2>Recent Searches</h2>
        <div class="search-list" id="searchList"></div>
      </section>
    </main>

    <!-- JavaScript -->
    <script>

      const urlForm = document.getElementById("urlForm");
      const urlInput = document.getElementById("urlInput");
      const analyzeBtn = document.getElementById("analyzeBtn");
      const spinner = document.getElementById("analyzeSpinner");
      const urlError = document.getElementById("urlError");

      const resultModal = document.getElementById("resultModal");
      const resultLabel = document.getElementById("resultLabel");
      const resultConfidence = document.getElementById("resultConfidence");
      const resultTip = document.getElementById("resultTip");

      urlForm.addEventListener("submit", function (e) {
        e.preventDefault();
        urlError.style.display = "none";

        const url = urlInput.value;
        try {
          new URL(url);
        } catch {
          urlError.style.display = "block";
          return;
        }

        analyzeBtn.disabled = true;
        spinner.style.display = "inline-block";

        fetch("save-url.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "url=" + encodeURIComponent(url)
        })
        .then(response => response.json())
        .then(data => {
  analyzeBtn.disabled = false;
  spinner.style.display = "none";

  console.log("Response Data:", data);



  if (data.status === "success") {
    // Show confidence %
    resultConfidence.textContent = `Average Fake Confidence: ${data.avg_fake_confidence}%`;
    resultModal.style.display = "block";

    // Decide color & label
    const avg = Number(data.avg_fake_confidence);
    let badgeText = "", badgeColor = "";

    if (avg >= 0 && avg <= 40) {
      badgeText = "🟥 Likely Fake Review";
      badgeColor = "#e53e3e"; // Red
    } else if (avg > 40 && avg <= 70) {
      badgeText = "🟨 Suspicious Review";
      badgeColor = "#ecc94b"; // Yellow
    } else if (avg > 70 && avg <= 100) {
      badgeText = "🟩 Likely Real Review";
      badgeColor = "#38a169"; // Green
    } else {
      badgeText = "Unknown Confidence";
      badgeColor = "#718096";
    }

    // Show badge
    const badge = document.getElementById("resultBadge");
    badge.textContent = badgeText;
    badge.style.background = badgeColor;
    badge.style.color = "#fff";
    badge.style.padding = "0.5rem 1rem";
    badge.style.borderRadius = "8px";
    badge.style.textAlign = "center";
    badge.style.display = "inline-block";
    badge.style.margin = "0.5rem 0";
  } else {
    alert(data.message);
  }
})

        .catch(error => {
          console.error("Error:", error);
          analyzeBtn.disabled = false;
          spinner.style.display = "none";
        });
      });

      function fetchRecentSearches() {
        fetch("get_urls.php")
          .then(response => response.json())
          .then(data => {
            const searchList = document.getElementById("searchList");
            searchList.innerHTML = data
              .map(
                search => `
                  <div class="search-item">
                    <a href="#" class="search-item-url">${search.url}</a>
                    <span class="search-item-time">${new Date(search.created_at).toLocaleString()}</span>
                  </div>`
              ).join("");
          })
          .catch(error => console.error("Error:", error));
      }

      fetchRecentSearches();

      const userProfile = document.getElementById("userProfile");
      const logoutMenu = document.getElementById("logoutMenu");
      const logoutBtn = document.getElementById("logoutBtn");

      userProfile.addEventListener("click", () => {
        logoutMenu.style.display = logoutMenu.style.display === "block" ? "none" : "block";
      });

      logoutBtn.addEventListener("click", () => {
        window.location.href = "logout.php";
      });

      document.addEventListener("click", (event) => {
        if (!userProfile.contains(event.target)) {
          logoutMenu.style.display = "none";
        }
      });

      function fetchRecentSearches() {
        fetch("get_urls.php")
          .then(response => response.json())
          .then(data => {
            const searchList = document.getElementById("searchList");
            searchList.innerHTML = data
              .map(
                search => `
                  <div class="search-item">
                    <a href="#" class="search-item-url">${search.url}</a>
                    <span class="search-item-time">${new Date(search.created_at).toLocaleString()}</span>
                  </div>`
              ).join("");
          })
          .catch(error => console.error("Error:", error));
      }

      fetchRecentSearches();

      // const userProfile = document.getElementById("userProfile");
      // const logoutMenu = document.getElementById("logoutMenu");
      // const logoutBtn = document.getElementById("logoutBtn");

      userProfile.addEventListener("click", () => {
        logoutMenu.style.display = logoutMenu.style.display === "block" ? "none" : "block";
      });

      logoutBtn.addEventListener("click", () => {
        window.location.href = "logout.php";
      });

      document.addEventListener("click", (event) => {
        if (!userProfile.contains(event.target)) {
          logoutMenu.style.display = "none";
        }
      });
    </script>
  </body>
</html>
