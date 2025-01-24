<?php
session_start();
include('config.php');


if (isset($_POST['add_to_cart'])) {
    $gameID = $_POST['gameID'];
    $userID = $_SESSION['userID'];

    $sql = "SELECT * FROM cart WHERE userID = ? AND gameID = ?";
    $fetch = $conn->prepare($sql);
    $fetch->bind_param("ii", $userID, $gameID);
    $fetch->execute();
    $result = $fetch->get_result();

    if ($result->num_rows > 0) {

        $update_sql = "UPDATE cart SET quantity = quantity + 1 
                       WHERE userID = ? AND gameID = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("ii", $userID, $gameID);
        $stmt_update->execute();
    } else {

        $insert_sql = "INSERT INTO cart (userID, gameID, quantity) VALUES (?, ?, 1)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("ii", $userID, $gameID);
        $stmt_insert->execute();
    }

    header("Location: cart.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamers Hub - Games Page</title>
    <link rel="stylesheet" href="assets/css/games.css">
    <link rel="shortcut icon" href="assets/images/Logo.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </header>
</head>

<body class="dropdown" data-bs-theme="dark">
    <header>
        <div class="logo-section">
            <h1 class="site-name">Gamers Hub</h1>
            <img src="assets/images/logo.png" alt="logo image" class="logo">
            <button class="nav-toggle">☰</button>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="games.php" class="active">Games</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="game-container">
        <h1>Games</h1>
        <?php

        $sql = "SELECT * FROM games";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                echo '<div class="game">';
                echo '<h2 class="game-title">' . $row['title'] . '</h2>';
                echo '<div class="game-content">';
                echo '<img src="' . $row['image'] . '" alt="' . $row['title'] . '" class="game-image">';

                echo '<div class="game-details">';
                echo '<p class="description"><strong>Description:</strong> ' . $row['description'] . '</p>';
                echo '<p class="release-date"><strong>Release Date:</strong> ' . $row['release_date'] . '</p>';
                echo '<p class="price"><strong>Price:</strong> $' . $row['price'] . '</p>';


                echo '<form action="games.php" method="POST" >';
                echo '<input type="hidden" name="gameID" value="' . $row['gameID'] . '">';
                echo '<button type="submit" name="add_to_cart" class="btn btn-warning">Add to Cart</button>';
                echo '</form>';

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<h3>No released Games</h3>';
        }
        ?>
    </div>

    <footer>
        <div class="social-links">
            <a href="https://www.facebook.com/" target="_blank">Facebook</a>
            <a href="https://x.com/" target="_blank">Twitter</a>
            <a href="https://www.instagram.com/" target="_blank">Instagram</a>
        </div>
        <p>&copy; 2025 Gamers Hub. All rights reserved.</p>
    </footer>

    <script>
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