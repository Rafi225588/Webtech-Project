<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Store the current page URL
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "LAW_PROJECT";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume user_id comes from session after user logs in

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user data
$sql_user = "SELECT * FROM USER WHERE user_id = $user_id";
$result_user = $conn->query($sql_user);
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    echo "No user found.";
    exit();
}

// Handle profile update when form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // SQL query to update user profile data
    $sql_update = "UPDATE USER SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone = '$phone' WHERE user_id = $user_id";

    if ($conn->query($sql_update) === TRUE) {
        header("Location: user_profile.php"); // Redirect to refresh the page and show updated profile
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

// Fetch pending hire requests
$sql_pending_requests = "SELECT M.message_id, M.message_text, M.sent_at, U.first_name AS lawyer_first_name, U.last_name AS lawyer_last_name, H.status, H.hire_id
                         FROM Message M
                         JOIN Hire H ON M.hire_id = H.hire_id
                         JOIN USER U ON M.sender_id = U.user_id
                         WHERE H.user_id = '$user_id' AND H.status = 'Pending'
                         ORDER BY M.sent_at DESC";
$result_pending_requests = $conn->query($sql_pending_requests);
$pending_requests = [];
while ($row = $result_pending_requests->fetch_assoc()) {
    $pending_requests[] = $row;
}

// Fetch messages for accepted hires
$sql_messages = "SELECT M.message_id, M.message_text, M.sent_at, U.first_name, U.last_name, H.hire_id
                 FROM Message M
                 JOIN Hire H ON M.hire_id = H.hire_id
                 JOIN USER U ON M.sender_id = U.user_id
                 WHERE H.user_id = '$user_id' AND H.status = 'Accepted'
                 ORDER BY M.sent_at ASC";
$result_messages = $conn->query($sql_messages);
$messages = [];
while ($row = $result_messages->fetch_assoc()) {
    $messages[$row['hire_id']][] = $row; // Group messages by hire_id
}

// Handle message submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $hire_id = intval($_POST['hire_id']);
    $message_text = mysqli_real_escape_string($conn, $_POST['message_text']);
    $sender_id = $user_id; // The user sends the message

    if (!empty($message_text)) {
        $sql_insert_message = "INSERT INTO Message (hire_id, sender_id, message_text) 
                               VALUES ('$hire_id', '$sender_id', '$message_text')";
        if ($conn->query($sql_insert_message) === TRUE) {
            echo "<script>alert('Message sent successfully.');</script>";
        } else {
            echo "<script>alert('Failed to send the message.');</script>";
        }
    } else {
        echo "<script>alert('Message cannot be empty.');</script>";
    }
    header("Location: user_profile.php"); // Redirect to prevent form resubmission
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile_picture'])) {
    // Set the upload directory for profile pictures
    $uploadDir = 'uploads/profile_pictures/';
    $uploadFile = $uploadDir . basename($_FILES['profile_picture']['name']);

    // Ensure the uploads directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Check if file is an image
    $check = getimagesize($_FILES['profile_picture']['tmp_name']);
    if ($check === false) {
        echo "<script>alert('File is not an image.');</script>";
    } else {
        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            // Get the filename and store it in the database
            $profilePictureUrl = basename($_FILES['profile_picture']['name']);

            // Update the database with the new profile picture filename
            $sql_update_picture = "UPDATE USER SET profile_picture_url = '$profilePictureUrl' WHERE user_id = $user_id";
            if ($conn->query($sql_update_picture) === TRUE) {
                echo "<script>alert('Profile picture updated successfully.');</script>";
                header("Location: user_profile.php"); // Refresh to reflect the change
                exit();
            } else {
                echo "<script>alert('Failed to update profile picture in the database.');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload the profile picture.');</script>";
        }
    }
}
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="user_profile.css?<?php echo time(); ?>">
    <style>
        /* Add styles for the circle profile picture */
        .profile-pic-container {


            overflow: hidden;
            margin-bottom: 20px;
        }


        /* Edit button styling */
        .edit-button {
            display: none;
        }

        .save-button {
            display: none;
        }

        .profile-pic-save-button {
            display: none;
            /* Initially hidden */
        }

        .form-group input[readonly] {
            background-color: #f0f0f0;
        }

        .logout-btn {
            margin: 20px 0;
            text-align: right;
            /* Aligns the button to the right */
        }

        .logout-btn button {
            padding: 10px 15px;
            background-color: #e74c3c;
            /* Red background */
            color: white;
            /* White text */
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .logout-btn button:hover {
            background-color: #c0392b;
            /* Darker red on hover */
        }
    </style>
</head>

<body>

    <header>
        <div class="header-container">
            <img src="logo.png" alt="Logo" id="logo">
            <div class="header-buttons">
                <a href="lawyer_page.php"><button class="header-button">Professional Help</button></a>
                <a href="post.php"><button class="header-button">Post</button></a>
                <a href="law_page.php"><button class="header-button">Back</button></a>
                <div class="logout-btn">
                    <form action="logout.php" method="POST">
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="user_profile" style="background-image: url('professional_background.jpg'); background-size: cover; background-position: center; background-attachment: fixed; height: 100vh; width: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column; margin: 0; padding: 0; color: white;">

        <div class="profile-container">
            <h1>User Profile</h1>
            <button id="message-button">Message</button>
            <button id="edit-profile-button" onclick="enableEditMode()">Edit Profile</button>

            <div class="profile-pic-container">
                <img src="uploads/profile_pictures/<?php echo $user['profile_picture_url']; ?>" alt="Profile Picture" class="profile-pic">

                <!-- Hidden File Input for Profile Picture Upload -->
                <input type="file" id="profile-pic-input" name="profile_picture" style="display: none;" accept="image/*" onchange="previewProfilePic(event)">

                <!-- Edit button to trigger file input -->
                <button class="edit-button" onclick="document.getElementById('profile-pic-input').click();">Edit</button>

                <!-- Save button to save the uploaded picture -->
                <form id="profile-pic-form" method="POST" action="user_profile.php" enctype="multipart/form-data">
                    <label for="profile_picture">Choose a profile picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                    <button type="submit" name="save_profile_picture">Upload Picture</button>
                </form>
            </div>

            <form method="POST" action="user_profile.php" class="profile-form">
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" id="first_name" readonly>
                    <button class="edit-button" type="button" onclick="enableEdit('first_name')">Edit</button>
                    <button class="save-button" type="submit" name="update_profile">Save</button>
                </div>

                <!-- New fields: Last Name, Phone Number, Email -->
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" id="last_name" readonly>
                    <button class="edit-button" type="button" onclick="enableEdit('last_name')">Edit</button>
                    <button class="save-button" type="submit" name="update_profile">Save</button>
                </div>

                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" id="phone" readonly>
                    <button class="edit-button" type="button" onclick="enableEdit('phone')">Edit</button>
                    <button class="save-button" type="submit" name="update_profile">Save</button>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" id="email" readonly>
                    <button class="edit-button" type="button" onclick="enableEdit('email')">Edit</button>
                    <button class="save-button" type="submit" name="update_profile">Save</button>
                </div>
            </form>
        </div>

        <script>
            function enableEdit(fieldId) {
                document.getElementById(fieldId).removeAttribute('readonly');
            }

            // Optional: Save profile picture logic (if needed)
            function saveProfilePic() {
                // Implement the logic to save the new profile picture.
                alert("Profile picture saved!");
            }

            // Optional: Preview profile picture logic
            function previewProfilePic(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.querySelector('.profile-pic');
                    output.src = reader.result;

                    // Show the Save button after selecting a new picture
                    document.querySelector('.profile-pic-save-button').style.display = 'inline-block';
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        </script>

    </div>


    <!-- Message Panel HTML -->
    <div id="message-panel">
        <h2>Message Panel</h2>

        <!-- Pending Hire Requests -->
        <!-- <div id="pending-requests">
            <h3>Pending Hire Requests</h3>
            <?php if (count($pending_requests) > 0): ?>
                <ul>
                    <?php foreach ($pending_requests as $request): ?>
                        <li>
                            Lawyer: <?= htmlspecialchars($request['lawyer_first_name']) ?> <?= htmlspecialchars($request['lawyer_last_name']) ?>
                            <span>Status: <?= htmlspecialchars($request['status']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No pending requests.</p>
            <?php endif; ?>
        </div> -->

        <!-- Messages Section -->
        <div id="messages">
            <h3>Messages</h3>
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $hire_id => $msgs): ?>
                    <div class="message-thread">
                        <h4>Messages with Lawyer</h4>
                        <h4>
                            Chat with <?php echo $user_name; ?>
                            <button type="button" class="minimize-chat" data-hire-id="<?php echo $hire_id; ?>">Minimize</button>
                        </h4>
                        <?php foreach ($msgs as $msg): ?>
                            <p class="thisMsg"><strong><?= htmlspecialchars($msg['first_name']) ?> <?= htmlspecialchars($msg['last_name']) ?>:</strong> <?= htmlspecialchars($msg['message_text']) ?> <em>(Sent at <?= $msg['sent_at'] ?>)</em></p>
                        <?php endforeach; ?>

                        <!-- Message Form -->
                        <form action="user_profile.php" method="POST">
                            <textarea name="message_text" required placeholder="Write your message here"></textarea>
                            <input type="hidden" name="hire_id" value="<?= $hire_id ?>">
                            <button type="submit" name="send_message">Send Message</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No messages found.</p>
            <?php endif; ?>
        </div>

        <!-- Close Button -->
        <button id="close-message-panel">Close</button>
    </div>
    <script>
        function previewProfilePic(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const profilePic = document.querySelector('.profile-pic');
                profilePic.src = reader.result;

                // Show the Save button after selecting a new picture
                document.querySelector('.profile-pic-save-button').style.display = 'inline-block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function enableEditMode() {
            document.querySelectorAll('.edit-button').forEach(button => {
                button.style.display = 'inline-block';
            });
            document.getElementById('edit-profile-button').style.display = 'none';
        }

        document.querySelectorAll('.form-group').forEach(group => {
            const editButton = group.querySelector('.edit-button');
            const saveButton = group.querySelector('.save-button');
            const input = group.querySelector('input');

            editButton.addEventListener('click', () => {
                input.removeAttribute('readonly');
                input.focus();
                editButton.style.display = 'none';
                saveButton.style.display = 'inline-block';
            });
        });

        document.getElementById('message-button').addEventListener('click', () => {
            document.getElementById('message-panel').style.display = 'block';
        });

        document.getElementById('close-message-panel').addEventListener('click', () => {
            document.getElementById('message-panel').style.display = 'none';
        });

        function previewProfilePic(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const profilePic = document.querySelector('.profile-pic');
                profilePic.src = reader.result;

                // Show the Save button after selecting a new picture
                document.querySelector('.profile-pic-save-button').style.display = 'inline-block'; // Changed the class
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function saveProfilePic() {
            // Hide the Save button after saving changes
            document.querySelector('.profile-pic-save-button').style.display = 'none'; // Changed the class
            alert('Profile picture saved successfully!');

            // Add logic to handle actual saving (e.g., form submission or AJAX request)
        }
        document.getElementById("minimize-message-panel").addEventListener("click", function() {
            var panel = document.getElementById("message-panel");
            var isMinimized = panel.style.display === "none";

            // Toggle visibility of the message panel
            if (isMinimized) {
                panel.style.display = "block";
                this.textContent = "Minimize"; // Update button text
            } else {
                panel.style.display = "none";
                this.textContent = "Restore"; // Update button text
            }
        });

        // Close the message panel
        document.getElementById("close-message-panel").addEventListener("click", function() {
            document.getElementById("message-panel").style.display = "none";
        });
        document.querySelectorAll('.thisMsg').forEach(button => {
            button.addEventListener('click', function() {
                const hireId = this.dataset.hireId;
                const chatBox = document.getElementById(`chat-box-${hireId}`);
                if (chatBox.style.display === 'none') {
                    chatBox.style.display = 'block';
                    this.textContent = 'Minimize';
                } else {
                    chatBox.style.display = 'none';
                    this.textContent = 'Maximize';
                }
            });
        });
    </script>
    </div>

</body>

</html>