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

// Handle post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];
    $stmt = $conn->prepare("INSERT INTO `POST` (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $content);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo '<script>alert("Failed to submit the post.");</script>';
    }
    $stmt->close();
}

// Fetch posts with limited comments
$query = "
    SELECT p.post_id, p.user_id, p.content, p.created_at, c.comment_id, c.comment_content 
    FROM `POST` p 
    LEFT JOIN (
        SELECT * FROM `COMMENTS` ORDER BY comment_id ASC
    ) c ON p.post_id = c.post_id 
    ORDER BY p.created_at DESC";
$result = $conn->query($query);

$posts = [];
while ($row = $result->fetch_assoc()) {
    if (!isset($posts[$row['post_id']])) {
        $posts[$row['post_id']] = [
            'post_id' => $row['post_id'],
            'user_id' => $row['user_id'],
            'content' => $row['content'],
            'created_at' => $row['created_at'],
            'comments' => []
        ];
    }
    if ($row['comment_content']) {
        $posts[$row['post_id']]['comments'][] = [
            'comment_id' => $row['comment_id'],
            'comment_content' => $row['comment_content']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Page</title>
    <link rel="stylesheet" href="post.css?<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <!-- Post Button -->
        <div class="post-button" onclick="openPostForm()">Post</div>

        <!-- Post Form -->
        <div class="post-form" id="postForm">
            <div class="form-container">
                <button class="close-btn" onclick="closePostForm()">X</button>
                <h2>Create a Post</h2>
                <form method="POST" action="">
                    <textarea id="postContent" name="content" placeholder="Write your post..."></textarea>
                    <button type="submit" name="submit_post" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>

        <!-- Posts Section -->
        <div class="posts">
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="content"><?= htmlspecialchars($post['content']); ?></div>
                    <div class="time">Posted by User <?= $post['user_id']; ?> at <?= $post['created_at']; ?></div>
                    <div class="comment-section">
                        <!-- Add Comment Form -->
                        <form method="POST" action="add_comment.php">
                            <textarea placeholder="Write a comment..." name="comment_content" required></textarea>
                            <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
                            <button type="submit" name="submit_comment">Comment</button>
                        </form>

                        <!-- Display Comments -->
                        <div class="comments" id="comments-<?= $post['post_id']; ?>">
                            <?php foreach (array_slice($post['comments'], 0, 5) as $comment): ?>
                                <div><?= htmlspecialchars($comment['comment_content']); ?></div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Comment Buttons -->
                        <div class="comment-buttons">
                            <?php if (count($post['comments']) > 5): ?>
                                <button class="see-more" onclick="fetchMoreComments(<?= $post['post_id']; ?>)">See More</button>
                                <button class="see-less" onclick="seeLessComments(<?= $post['post_id']; ?>)" style="display: none;">See Less</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function openPostForm() {
            document.getElementById('postForm').classList.add('open');
        }

        function closePostForm() {
            document.getElementById('postForm').classList.remove('open');
        }

        function fetchMoreComments(postId) {
            const commentsContainer = document.getElementById(`comments-${postId}`);
            const offset = commentsContainer.childElementCount;

            fetch(`fetch_comments.php?post_id=${postId}&offset=${offset}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    // Append new comments to the comments container
                    data.comments.forEach(comment => {
                        const commentDiv = document.createElement('div');
                        commentDiv.textContent = comment.comment_content;
                        commentsContainer.appendChild(commentDiv);
                    });

                    // Toggle button visibility
                    if (!data.hasMore) {
                        document.querySelector(`.see-more[onclick='fetchMoreComments(${postId})']`).style.display = 'none';
                    }
                    document.querySelector(`.see-less[onclick='seeLessComments(${postId})']`).style.display = 'block';
                })
                .catch(error => console.error('Error fetching comments:', error));
        }

        function seeLessComments(postId) {
            const commentsContainer = document.getElementById(`comments-${postId}`);
            const allComments = Array.from(commentsContainer.children);
            const initialComments = allComments.slice(0, 5);

            // Remove extra comments beyond the first 5
            while (commentsContainer.firstChild) {
                commentsContainer.removeChild(commentsContainer.firstChild);
            }
            initialComments.forEach(comment => commentsContainer.appendChild(comment));

            // Toggle button visibility
            document.querySelector(`.see-more[onclick='fetchMoreComments(${postId})']`).style.display = 'block';
            document.querySelector(`.see-less[onclick='seeLessComments(${postId})']`).style.display = 'none';
        }
    </script>
</body>
</html>