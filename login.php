<?php
session_start();
include("config.php");

if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $fetch = $conn->prepare($sql);
    $fetch->bind_param('s', $username);
    $fetch->execute();
    $result = $fetch->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();


        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['userID'] = $row['userID'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamers Hub Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="shortcut icon" href="assets/images/Logo.png" type="image/x-icon">
</head>

<body>
    <div class="login-container">
        <h1>Welcome to Gamers Hub</h1>

        <?php if (!empty($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form id="loginForm" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="button">Submit</button>
        </form>
        <p class="signup-link">Don't have an account? <a href="signup.php">Sign up here</a>.</p>
    </div>
</body>

</html>