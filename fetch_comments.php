<?php
// Start session
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json");
    echo json_encode(['error' => 'Unauthorized access.']);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "LAW_PROJECT";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    header("Content-Type: application/json");
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

// Fetch comments for a specific post
if (isset($_GET['post_id']) && isset($_GET['offset'])) {
    $post_id = intval($_GET['post_id']);
    $offset = intval($_GET['offset']);
    $limit = 5; // Fetch 5 comments at a time

    $stmt = $conn->prepare("SELECT comment_id, comment_content FROM `COMMENTS` WHERE post_id = ? ORDER BY comment_id ASC LIMIT ?, ?");
    $stmt->bind_param("iii", $post_id, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    // Check if there are more comments to fetch
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM `COMMENTS` WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($total_comments);
    $stmt->fetch();

    $hasMore = ($offset + $limit) < $total_comments;

    header("Content-Type: application/json");
    echo json_encode(['comments' => $comments, 'hasMore' => $hasMore]);
    exit;
}

header("Content-Type: application/json");
echo json_encode(['error' => 'Invalid request.']);
exit;
?>