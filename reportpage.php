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
    <title>SecuroGuard - Report</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
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
        max-width: 1200px;
        margin: 100px auto 2rem;
        padding: 0 1rem;
      }

      .report-header {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
      }

      .report-header h1 {
        color: #2d3748;
        margin-bottom: 1rem;
        font-size: 1.8rem;
      }

      .product-info {
        display: flex;
        gap: 2rem;
        margin-bottom: 1.5rem;
      }

      .key-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
      }

      .metric-card {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
      }

      .metric-card h3 {
        color: #4a5568;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
      }

      .metric-card .value {
        color: #2d3748;
        font-size: 1.8rem;
        font-weight: bold;
      }

      .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
      }

      .chart-container {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .chart-container h2 {
        color: #2d3748;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
      }

      .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
      }

      .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .primary-btn {
        background: #764ba2;
        color: white;
        border: none;
      }

      .primary-btn:hover {
        background: #663c91;
      }

      .secondary-btn {
        background: white;
        color: #4a5568;
        border: 1px solid #e2e8f0;
      }

      .secondary-btn:hover {
        background: #f8fafc;
      }

      .tooltip {
        position: relative;
        display: inline-block;
        cursor: help;
      }

      .tooltip .tooltip-text {
        visibility: hidden;
        background-color: #2d3748;
        color: white;
        text-align: center;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        width: 200px;
        font-size: 0.9rem;
        opacity: 0;
        transition: opacity 0.3s;
      }

      .tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
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

      @media (max-width: 768px) {
        .charts-grid {
          grid-template-columns: 1fr;
        }

        .action-buttons {
          flex-direction: column;
        }

        .btn {
          width: 100%;
          justify-content: center;
        }
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
      <section class="report-header">
        <h1>Review Analysis Report</h1>
        <div class="product-info">
          <div>
            <strong>Product URL:</strong>
            <a href="#" style="color: #764ba2; text-decoration: none"
              >https://example.com/product</a
            >
          </div>
          <div>
            <strong>Analysis Date:</strong>
            <span>January 19, 2025</span>
          </div>
        </div>

        <div class="key-metrics">
          <div class="metric-card">
            <h3>Total Reviews Analyzed</h3>
            <div class="value">1,247</div>
          </div>
          <div class="metric-card">
            <h3>Fake Reviews Detected</h3>
            <div class="value tooltip">
              324
              <span class="tooltip-text"
                >26% of total reviews flagged as potentially fake</span
              >
            </div>
          </div>
          <div class="metric-card">
            <h3>Average Rating</h3>
            <div class="value">4.2/5</div>
          </div>
          <div class="metric-card">
            <h3>Confidence Score</h3>
            <div class="value tooltip">
              92%
              <span class="tooltip-text"
                >Confidence level in fake review detection</span
              >
            </div>
          </div>
        </div>
      </section>

      <div class="charts-grid">
        <div class="chart-container">
          <h2>Review Distribution</h2>
          <canvas id="reviewPieChart"></canvas>
        </div>
        <div class="chart-container">
          <h2>Review Trends</h2>
          <canvas id="reviewTrendChart"></canvas>
        </div>
      </div>

      <div class="action-buttons">
        <button class="btn primary-btn" id="downloadBtn">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="20"
            height="20"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
          </svg>
          Download Report
        </button>
        <button class="btn secondary-btn" id="analyzeNewBtn" onclick="window.location.href='index.php'">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="20"
            height="20"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          Analyze Another Product
        </button>
      </div>
    </main>

    <script>
      // Pie Chart
      const pieCtx = document.getElementById("reviewPieChart").getContext("2d");
      new Chart(pieCtx, {
        type: "pie",
        data: {
          labels: ["Genuine Reviews", "Suspicious Reviews"],
          datasets: [
            {
              data: [923, 324],
              backgroundColor: ["#48bb78", "#f56565"],
              borderColor: ["#ffffff", "#ffffff"],
              borderWidth: 2,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "bottom",
            },
          },
        },
      });

      // Line Chart
      const lineCtx = document
        .getElementById("reviewTrendChart")
        .getContext("2d");
      new Chart(lineCtx, {
        type: "line",
        data: {
          labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
          datasets: [
            {
              label: "Genuine Reviews",
              data: [65, 78, 82, 75, 85, 90],
              borderColor: "#48bb78",
              tension: 0.3,
              fill: false,
            },
            {
              label: "Suspicious Reviews",
              data: [25, 32, 28, 35, 30, 22],
              borderColor: "#f56565",
              tension: 0.3,
              fill: false,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "bottom",
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: "Number of Reviews",
              },
            },
            x: {
              title: {
                display: true,
                text: "Month",
              },
            },
          },
        },
      });

      // Button handlers
      document
        .getElementById("downloadBtn")
        .addEventListener("click", function () {
          alert("Downloading report...");
        });

      document
        .getElementById("analyzeNewBtn")
        .addEventListener("click", function () {
          window.location.href = "#"; // In a real app, this would redirect to the URL input page
        });

      // User profile and logout menu
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
