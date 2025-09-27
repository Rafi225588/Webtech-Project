<?php
// Database connection
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "LAW_PROJECT"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    $user_type = sanitizeInput($_POST['user']); // 'User' or 'Lawyer'
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate email format and check for '@gmail.com'
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_contains($email, '@gmail.com')) {
        echo "Invalid email format. Please use a @gmail.com email address.";
    }
    // Check if passwords match
    elseif ($password !== $confirm_password) {
        echo "Passwords do not match";
    }
    else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the USER table
        $stmt = $conn->prepare("INSERT INTO `USER` (first_name, last_name, email, user_type, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $user_type, $hashed_password);

        if ($stmt->execute()) {
            echo "Signup successful!";
            header("Location: login.php");
            // Redirect or perform additional actions if needed
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp_Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php 
  include 'header.php';
  ?>
    <!-- Signup Page -->
    <section id="signup-page" class="hidden" style="background-image: url('signup-background.jpg'); background-size: cover; background-position: center; background-attachment: fixed; height: 100vh; width: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column; margin: 0; padding: 0; color: white;">
    

        <!-- <header>
          <img src="logo.png" alt="Logo" id="logo" />
          <button class="home-button" id="home-signup-page">Home</button>
        </header> -->
        <main>
          <h1>Sign Up</h1>
          <form method="POST" action="">
            <input type="text" placeholder="First Name" name="first_name" required />
            <input type="text" placeholder="Last Name" name="last_name" required />
            <input type="email" placeholder="Email" name="email" required />
            <!-- <input type="text" placeholder="Address" name="address" required />
            <select name="district" required>
              <option value="">Select District</option>
              <option value="Dhaka">Dhaka</option>
              <option value="Chittagong">Chittagong</option>
              <option value="Rajshahi">Rajshahi</option>
              <option value="Khulna">Khulna</option>
            </select>
            <input type="text" placeholder="Zip Code" name="zip_code" required />
            <input type="text" placeholder="Upazila" name="upazila" required />
            <input type="text" placeholder="Thana" name="thana" required />
            <input type="text" placeholder="Country" name="country" value="Bangladesh" readonly required /> -->
            <input type="text" placeholder="UserType" value="User" name="user" readonly required />
            <input type="password" placeholder="Password" name="password" required />
            <input type="password" placeholder="Confirm Password" name="confirm_password" required />
            <button type="submit" id="signup-btn">Sign Up</button>
          </form>
          <p>
            Already have an account?
            <a href="login.php" id="switch-to-login">Login</a>
          </p>
        </main>
      </section>
      <?php include 'footer.php';
?>
</body>
</html>