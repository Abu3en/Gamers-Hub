<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Gamers Hub - Home</title>
  <link rel="stylesheet" href="assets/css/home.css">
  <link rel="shortcut icon" href="assets/images/Logo.png" type="image/x-icon">
  <?php
  session_start();
  include('config.php');
  if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
  }
  ?>
</head>

<body>
  <div>

  </div>
  <header>
    <div class="logo-section">
      <h1 class="site-name">Gamers Hub</h1>
      <?php
      $name = htmlspecialchars($_SESSION['username']);
      echo '<h4 style="color: #ffa726">' . $name . '</h4>';
      ?>
      <img src="assets/images/logo.png" alt="logo image" class="logo">
      <button class="nav-toggle">☰</button>
    </div>
    <nav>
      <ul>
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="games.php">Games</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="logout.php">Log Out</a></li>

      </ul>
    </nav>
  </header>

  <img src="assets\images\HeroImage.png" alt="Hero Image" class="hero-image">

  <section class="latest-game">
    <h2>Latest Released Game</h2>
    <?php
    $sql = "SELECT title, image, description, release_date 
        FROM games 
        WHERE release_date = (SELECT MAX(release_date) FROM games);";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo '<div class="game" >';

        echo '<h2 class="game-title">' . $row['title'] . '</h2>';

        echo '<div class="game-content"' . '>';

        echo '<img src="' . $row['image'] . '" alt="' . $row['title'] . '" class="game-image">';

        echo '<div class="game-details"' . '>';
        echo '<p class="description"><strong>Description:</strong> ' . $row['description'] . '</p>';
        echo '<p class="release-date"><strong>Release Date:</strong> ' . $row['release_date'] . '</p>';

        echo '</div>';
        echo '</div>';
        echo '</div>';
      }
    } else {
      echo "No released Games";
    }
    ?>


    <a href="games.php" class="view-all">View all games</a>

  </section>

  <footer>
    <div class="social-links">
      <a href="https://www.facebook.com/" target="_blank">Facebook</a>
      <a href="https://x.com/" target="_blank">Twitter</a>
      <a href="https://www.instagram.com/" target="_blank">Instagram</a>
    </div>
    <p>&copy; 2025 Gamers Hub. All rights reserved.</p>
  </footer>
  <script>
    window.alert("Welcome to Gamers Hub");
    document.addEventListener("DOMContentLoaded", function () {
      const navToggle = document.querySelector(".nav-toggle");
      const navMenu = document.querySelector("nav ul");

      navToggle.addEventListener("click", function () {
        navMenu.classList.toggle("show");
      });
    });
  </script>
</body>


</html>