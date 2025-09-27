<?php
// Start the session to handle session-based operations if needed.
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if username and password are correct
    if ($username == "admin" && $password == "admin12") {
        // Set session variable to track the logged-in user
        $_SESSION['admin_id'] = $username;

        // Redirect to index.php
        header('Location: index.php');
        exit(); // Always call exit after header redirection
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="adminLogin.css">
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="adminLogin.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>
            <?php
            if (!empty($error)) {
                echo "<p style='color: red; margin-bottom: 10px;'>$error</p>";
            }
            ?>
            <button type="submit" name="loginBtn" class="btn">Login</button>
        </form>
        
    </div>
</body>

</html>