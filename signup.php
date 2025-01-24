<?php
session_start();
include("config.php");

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $conPass = $_POST['confirmPassword'];

    if ($pass !== $conPass) {
        $error = "Passwords do not match!";
    } else {
        $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $fetch = $conn->prepare($sql);
        $fetch->bind_param('s', $username);
        $fetch->execute();
        $result = $fetch->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already taken!";
        } else {
            $hashPass = password_hash($pass, PASSWORD_DEFAULT);



            $insertSql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param('ss', $username, $hashPass);

            if ($insertStmt->execute()) {
                $_SESSION['userID'] = $conn->insert_id;
                $_SESSION['username'] = $username;
                header("Location: index.php");

                exit;
            } else {
                $error = "Error during sign up: " . $conn->error;
            }
        }


    }
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamers Hub Sign Up</title>
    <link rel="stylesheet" href="assets/css/signup.css">
    <link rel="shortcut icon" href="assets/images/Logo.png" type="image/x-icon">

</head>

<body>
    <div class="login-container">
        <h1>Create Your XP Market Account</h1>

        <?php if (!empty($error)): ?>
            <div>
                <strong style="color:red;"><?php echo $error ?></strong>
            </div>
            </p>
        <?php endif; ?>

        <form id="signupForm" method="POST" action="">
            <div class="form">
                <span id="message" style="color: red; margin-bottom: 10px;"></span>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" required>
            </div>
            <div class="form">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password" required>
            </div>
            <div class="form">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password"
                    required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <p class="signup-link">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>

    <script src="assets\javascript\scripts.js"></script>
</body>

</html>