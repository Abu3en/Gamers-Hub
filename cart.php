<?php
session_start();
include('config.php');

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['increase_qty'])) {

    $gameID = $_POST['gameID'];
    $userID = $_SESSION['userID'];

    $sql = "UPDATE cart SET quantity = quantity + 1
                WHERE userID = ? AND gameID = ?";
    $increase = $conn->prepare($sql);
    $increase->bind_param('ii', $userID, $gameID);
    $increase->execute();

    header("Location: cart.php");
    exit();
}

if (isset($_POST['decrease_qty'])) {

    $gameID = $_POST['gameID'];
    $userID = $_SESSION['userID'];

    $sql = "UPDATE cart SET quantity = quantity - 1
                WHERE userID = ? AND gameID = ?";
    $decrease = $conn->prepare($sql);
    $decrease->bind_param('ii', $userID, $gameID);
    $decrease->execute();

    $check_sql = "SELECT quantity FROM cart WHERE userID = ? AND gameID = ?";
    $quantityStatement = $conn->prepare($check_sql);
    $quantityStatement->bind_param('ii', $userID, $gameID);
    $quantityStatement->execute();
    $res = $quantityStatement->get_result();

    if ($res->num_rows > 0) {
        $row_check = $res->fetch_assoc();
        if ($row_check['quantity'] <= 0) {
            $del_sql = "DELETE FROM cart WHERE userID = ? AND gameID = ?";
            $stmt_del = $conn->prepare($del_sql);
            $stmt_del->bind_param('ii', $userID, $gameID);
            $stmt_del->execute();
        }
    }

    header("Location: cart.php");
    exit();
}

if (isset($_POST['remove_item'])) {
    $gameID = $_POST['gameID'];
    $userID = $_SESSION['userID'];

    $sql = "DELETE FROM cart WHERE userID = ? AND gameID = ?";
    $fetch = $conn->prepare($sql);
    $fetch->bind_param('ii', $userID, $gameID);
    $fetch->execute();

    header("Location: cart.php");
    exit();
}


$sql2 = "SELECT c.quantity, g.gameID, g.title, g.price, g.image
        FROM cart c
        JOIN games g ON c.gameID = g.gameID
        WHERE c.userID = ?";
$fetch2 = $conn->prepare($sql2);
$fetch2->bind_param("i", $_SESSION['userID']);
$fetch2->execute();
$result = $fetch2->get_result();

$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gamers Hub - Cart</title>
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="shortcut icon" href="assets/images/Logo.png" type="image/x-icon">
</head>

<body>
    <header>
        <div class="logo-section">
            <h1 class="site-name">Gamers Hub</h1>
            <img src="assets/images/logo.png" alt="logo image" class="logo">
            <button class="nav-toggle">☰</button>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="games.php">Games</a></li>
                <li><a href="cart.php " class="active">Cart</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="game-container">
        <h1>Your Cart</h1>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $gameID = $row['gameID'];
                $title = $row['title'];
                $price = $row['price'];
                $quantity = $row['quantity'];
                $image = $row['image'];

                $gamesPrice = $price * $quantity;
                $totalPrice += $gamesPrice;

                echo '<div class="game">';
                echo '<h2 class="game-title">' . $title . '</h2>';
                echo '<div class="game-content">';
                echo '<img src="' . $image . '" alt="' . $title . '" class="game-image">';
                echo '<div class="game-details">';

                echo '<p><strong>Price:</strong> $' . $price . '</p>';
                echo '<p><strong>Quantity:</strong> ' . $quantity . '</p>';
                echo '<p><strong>Total:</strong> $' . $gamesPrice . '</p>';

                echo '<div class="buttons">';
                echo '<form method="POST" action="cart.php" class="buttons">';
                echo '<input type="hidden" name="gameID" value="' . $gameID . '">';
                echo '<button type="submit" name="increase_qty">+</button>';
                echo '</form>';

                echo '<form method="POST" action="cart.php" class="buttons" >';
                echo '<input type="hidden" name="gameID" value="' . $gameID . '">';
                echo '<button type="submit" name="decrease_qty">-</button>';
                echo '</form>';
                echo '</div>';

                echo '<form method="POST" action="cart.php" >';
                echo '<input type="hidden" name="gameID" value="' . $gameID . '">';
                echo '<button type="submit" name="remove_item" class="remove-button">Remove</button>';
                echo '</form>';

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }


            echo '<div class="cart-total"">';
            echo '  <h2>Total Price: $' . $totalPrice . '</h2>';
            echo '</div>';
        } else {
            echo '<h3 class="empty-cart">Added games will appear in your cart</h3>';
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