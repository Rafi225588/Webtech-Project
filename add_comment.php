<?php
// Start session
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "LAW_PROJECT";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment_content = $_POST['comment_content'];

    // Prevent SQL injection by using prepared statements
    $stmt = $conn->prepare("INSERT INTO `COMMENTS` (post_id, user_id, comment_content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $post_id, $user_id, $comment_content);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect to the previous page
        exit();
    } else {
        echo '<script>alert("Failed to submit the comment.");</script>';
    }

    $stmt->close();
}

$conn->close();
?>