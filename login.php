<?php
session_start();

// Redirect to lawyer_page.php if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: lawyer_page.php");
    exit();
}

include 'connection.php';

if (isset($_POST['loginbtn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT user_id, password, user_type FROM USER WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userId, $hashed_password, $user_type);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $userId; // Store user ID in session
            $_SESSION['user_type'] = $user_type; // Store user type in session

            // Redirect to lawyer_page.php
            header("Location: lawyer_page.php");
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found with this email!";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <!-- Login Page -->
    <section id="login-page">
        <main>
            <h1>Login</h1>
            <form method="POST" action="login.php">
                <input type="email" placeholder="Email" name="email" required />
                <div class="password-container">
                    <input type="password" placeholder="Password" name="password" id="password" required />
                    <span class="toggle-password" onclick="togglePassword()">👁️</span>
                </div>
                <button name='loginbtn' type="submit" id="login-btn">Login</button>
            </form>
            <?php
            // Display error message if there is one
            if (isset($error_message)) {
                echo "<p class='error-message'>$error_message</p>";
            }
            ?>
            <p>
                Don’t have an account?
                <a href="signup.php" id="switch-to-signup">Sign Up</a>
            </p>
        </main>
    </section>

    <?php include 'footer.php'; ?>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>

</body>

</html>