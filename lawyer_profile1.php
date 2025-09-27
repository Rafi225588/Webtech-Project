<?php
session_start();
include('connection.php'); // Include your database connection

// Assuming the user is logged in and their session is active
$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM USER WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Fetch lawyer details to get the lawyer_id
$query_lawyer = "SELECT * FROM LAWYER WHERE lawyer_id = '$user_id'";
$result_lawyer = mysqli_query($conn, $query_lawyer);
$lawyer = mysqli_fetch_assoc($result_lawyer);

if (!$lawyer) {
    echo "Lawyer details not found.";
    exit;
}

// Handle form submission for inserting law
if (isset($_POST['insert_law'])) {
    // Get form data
    $law_index = mysqli_real_escape_string($conn, $_POST['law_index']);
    $short_description = mysqli_real_escape_string($conn, $_POST['short_description']);
    $full_description = mysqli_real_escape_string($conn, $_POST['full_description']);

    // Fetch the lawyer_id from the lawyer table
    $lawyer_id = $lawyer['lawyer_id'];

    // Insert law into laws table
    $query_insert_law = "INSERT INTO laws (law_index, short_description, full_description, lawyer_id) 
                         VALUES ('$law_index', '$short_description', '$full_description', '$lawyer_id')";

    if (mysqli_query($conn, $query_insert_law)) {
        echo "Law inserted successfully!";
        header("Location: lawyer_profile1.php"); // Redirect after success
        exit; // Ensure script stops execution after redirect
    } else {
        echo "Error inserting law: " . mysqli_error($conn);
    }
}



// Handle profile updates (name, phone, etc.)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nid = mysqli_real_escape_string($conn, $_POST['nid']);
    $charge = mysqli_real_escape_string($conn, $_POST['charge']);
    $license = mysqli_real_escape_string($conn, $_POST['license']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Update user information in the USER table
    $query_update_user = "UPDATE USER SET first_name = '$first_name', last_name = '$last_name', phone = '$phone', email = '$email' WHERE user_id = '$user_id'";
    if (mysqli_query($conn, $query_update_user)) {
        echo "User info updated successfully!";
    } else {
        echo "Error updating user info: " . mysqli_error($conn);
    }

    // Update lawyer information in the LAWYER table
    $query_update_lawyer = "UPDATE LAWYER SET nid = '$nid',charge = '$charge', license = '$license', experience = '$experience', address = '$address', phone = '$phone' WHERE lawyer_id = '$user_id'";
    if (mysqli_query($conn, $query_update_lawyer)) {
        echo "Lawyer info updated successfully!";
    } else {
        echo "Error updating lawyer info: " . mysqli_error($conn);
    }
    // Redirect to prevent form resubmission
    header("Location: lawyer_profile1.php");
    exit; //
}

// Fetch pending hire requests for the logged-in lawyer
$lawyer_id = $_SESSION['user_id']; // Assuming lawyer_id is stored in the session
$query = "
    SELECT M.message_id, M.message_text, M.sent_at, U.first_name, U.last_name, H.status, H.hire_id
    FROM Message M
    JOIN Hire H ON M.hire_id = H.hire_id
    JOIN USER U ON M.sender_id = U.user_id
    WHERE H.lawyer_id = '$lawyer_id' AND H.status = 'Pending'
    ORDER BY M.sent_at DESC";

$result = mysqli_query($conn, $query);

// Handle accept/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accept_hire'])) {
        $hire_id = $_POST['hire_id'];
        $update_query = "UPDATE Hire SET status = 'Accepted' WHERE hire_id = '$hire_id'";
        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Hire request accepted.');</script>";
        } else {
            echo "<script>alert('Failed to accept the hire request.');</script>";
        }
    }

    if (isset($_POST['reject_hire'])) {
        $hire_id = $_POST['hire_id'];
        $update_query = "UPDATE Hire SET status = 'Rejected' WHERE hire_id = '$hire_id'";
        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Hire request rejected.');</script>";
        } else {
            echo "<script>alert('Failed to reject the hire request.');</script>";
        }
        header("Location: lawyer_profile1.php");
        exit; // Redirect and stop script execution
    }
}
//perfect
// Fetch accepted hire requests and their messages for the logged-in lawyer
$lawyer_id = $_SESSION['user_id']; // Assuming lawyer_id is stored in the session
$query_messages = "
    SELECT M.message_id, M.message_text, M.sent_at, U.first_name, U.last_name, H.hire_id
    FROM Message M
    JOIN Hire H ON M.hire_id = H.hire_id
    JOIN USER U ON M.sender_id = U.user_id
    WHERE H.lawyer_id = '$lawyer_id' AND H.status = 'Accepted'
    ORDER BY M.sent_at ASC";
$result_messages = mysqli_query($conn, $query_messages);

$messages = [];
while ($row = mysqli_fetch_assoc($result_messages)) {
    $messages[$row['hire_id']][] = $row; // Group messages by hire ID
}

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $hire_id = intval($_POST['hire_id']);
    $message_text = mysqli_real_escape_string($conn, $_POST['message_text']);

    if (!empty($message_text)) {
        $query_insert_message = "INSERT INTO Message (hire_id, sender_id, message_text) VALUES ('$hire_id', '$lawyer_id', '$message_text')";
        if (mysqli_query($conn, $query_insert_message)) {
            echo "<script>alert('Message sent successfully.');</script>";
        } else {
            echo "<script>alert('Failed to send the message.');</script>";
        }
        // Redirect to prevent form resubmission
        header("Location: lawyer_profile1.php");
        exit();
    } else {
        echo "<script>alert('Message cannot be empty.');</script>";
    }
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
                header("Location: lawyer_profile1.php"); // Refresh to reflect the change
                exit();
            } else {
                echo "<script>alert('Failed to update profile picture in the database.');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload the profile picture.');</script>";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lawyer Profile</title>
    <link rel="stylesheet" href="lawyer_profile1.css?<?php echo time(); ?>">
    <style>
        /* Add styles for the circle profile picture */
        .profile-pic-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .header-container {
            background: #2c3e50;
        }

       
        .mainContainer {
            background:rgb(150, 198, 247);
            margin: 0;
            padding: 0;
            color: white;
        }
        .lawyer_profile{
            padding: 10px 200px 100px 200px;
        }
    </style>
</head>

<body>
    <div class="mainContainer">
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

        <div class="lawyer_profile">
            <div class="profile-container">
                <h1>Lawyer Profile</h1>
                <button id="message-button">Message</button>
                <button id="edit-profile-button" onclick="enableEditMode()">Edit Profile</button>
                <button id="insert-law-button" type="submit" name="insert_law" onclick="toggleInsertLawForm()">Insert Law</button>

                <div class="profile-pic-container">
                    <img src="uploads/profile_pictures/<?php echo $user['profile_picture_url']; ?>" alt="Profile Picture" class="profile-pic">
                    <input type="file" id="profile-pic-input" class="profile-pic-input" style="display: none" accept="image/*" onchange="previewProfilePic(event)">
                    <button class="edit-button" onclick="document.getElementById('profile-pic-input').click();">Edit</button>
                    <button class="profile-pic-save-button" onclick="saveProfilePic()">Save</button>
                </div>
                <form id="profile-pic-form" method="POST" action="lawyer_profile1.php" enctype="multipart/form-data">
                    <label for="profile_picture">Choose a profile picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                    <button type="submit" name="save_profile_picture">Upload Picture</button>
                </form>
                <form method="POST" action="lawyer_profile1.php" class="profile-form">
                    <div class="form-group">
                        <label>First Name:</label>
                        <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" readonly>
                        <button class="edit-button" type="button">Edit</button>
                        <button class="save-button" type="submit" name="update_profile">Save</button>
                    </div>

                    <div class="form-group">
                        <label>Last Name:</label>
                        <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" readonly>
                        <button class="edit-button" type="button">Edit</button>
                        <button class="save-button" type="submit" name="update_profile">Save</button>
                    </div>

                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="text" name="phone" value="<?php echo $lawyer['phone']; ?>" readonly>
                        <button class="edit-button" type="button">Edit</button>
                        <button class="save-button" type="submit" name="update_profile">Save</button>
                    </div>

                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo $user['email']; ?>" readonly>
                        <button class="edit-button" type="button">Edit</button>
                        <button class="save-button" type="submit" name="update_profile">Save</button>
                    </div>

                    <div class="form-group">
                        <label>NID:</label>
                        <input type="text" name="nid" value="<?php echo $lawyer['nid']; ?>" readonly>
                        <button class="edit-button" type="button">Edit</button>
                        <button class="save-button" type="submit" name="update_profile">Save</button>
                    </div>

                    <div class="form-group">
                        <label>Charge:</label>
                        <input type="text" name="charge" value="<?php echo $lawyer['charge']; ?>" readonly>
                        <button class="edit-button" type="button">Edit</button>
                        <button class="save-button" type="submit" name="update_profile">Save</button>
                    </div>

                    <div class="form-group">
                        <label>License:</label>
                        <input type="text" name="license" value="<?php echo $lawyer['license']; ?>" readonly>
                        <button class="edit-button" type="button">Edit</button>
                        <button class="save-button" type="submit" name="update_profile">Save</button>
                    </div>

                    <div class="form-group">
                        <label>Experience:</label>
                        <input type="text" name="experience" value="<?php echo $lawyer['experience']; ?>" readonly>
                        <button class="edit-button" type="button">Edit</button>
                        <button class="save-button" type="submit" name="update_profile">Save</button>
                    </div>

                    <div class="form-group">
                        <label>Address:</label>
                        <input type="text" name="address" value="<?php echo $lawyer['address']; ?>" readonly>
                        <button class="edit-button" type="button">Edit</button>
                        <button class="save-button" type="submit" name="update_profile">Save</button>
                    </div>

                </form>
            </div>

            <div id="message-panel" style="display: none;">
                <h2>Message Panel</h2>
                <button id="close-message-panel">Close</button>
                <div class="message-list">
                    <!-- Pending Hire Requests Section -->
                    <div>
                        <h3>Pending Hire Requests</h3>
                        <?php
                        $lawyer_id = $_SESSION['user_id']; // Assuming lawyer_id is stored in the session
                        $pending_query = "
                SELECT H.hire_id, U.first_name, U.last_name
                FROM Hire H
                JOIN USER U ON H.user_id = U.user_id
                WHERE H.lawyer_id = '$lawyer_id' AND H.status = 'Pending'
                ORDER BY H.hire_date DESC";

                        $pending_result = mysqli_query($conn, $pending_query);

                        if (mysqli_num_rows($pending_result) > 0) {
                            while ($row = mysqli_fetch_assoc($pending_result)) {
                        ?>
                                <div class="message">
                                    <p><?php echo $row['first_name'] . ' ' . $row['last_name']; ?> wants to hire you.</p>
                                    <form method="POST">
                                        <input type="hidden" name="hire_id" value="<?php echo $row['hire_id']; ?>">
                                        <button type="submit" name="accept_hire" class="accept-button">Accept</button>
                                        <button type="submit" name="reject_hire" class="reject-button">Reject</button>
                                    </form>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p>No pending hire requests.</p>";
                        }
                        ?>
                    </div>

                    <!-- Active Chats Section -->
                    <div>
                        <h3>Active Chats</h3>
                        <?php
                        $active_query = "
                SELECT H.hire_id, U.first_name, U.last_name
                FROM Hire H
                JOIN USER U ON H.user_id = U.user_id
                WHERE H.lawyer_id = '$lawyer_id' AND H.status = 'Accepted'
                ORDER BY H.hire_date DESC";

                        $active_result = mysqli_query($conn, $active_query);

                        if (mysqli_num_rows($active_result) > 0) {
                            while ($row = mysqli_fetch_assoc($active_result)) {
                                $hire_id = $row['hire_id'];
                                $user_name = $row['first_name'] . ' ' . $row['last_name']; // User's full name

                                // Fetch messages for the current hire_id
                                $message_query = "
                        SELECT M.message_text, M.sent_at, U.first_name, U.last_name
                        FROM Message M
                        JOIN USER U ON M.sender_id = U.user_id
                        WHERE M.hire_id = '$hire_id'
                        ORDER BY M.sent_at ASC";
                                $message_result = mysqli_query($conn, $message_query);
                        ?>
                                <div class="chat-container" id="chat-<?php echo $hire_id; ?>" style="border: 1px solid #ccc; margin-bottom: 20px; padding: 10px;">
                                    <div class="chat-header">
                                        <h4>
                                            Chat with <?php echo $user_name; ?>
                                            <button type="button" class="minimize-chat" data-hire-id="<?php echo $hire_id; ?>">Minimize</button>
                                        </h4>
                                    </div>
                                    <div class="chat-box" id="chat-box-<?php echo $hire_id; ?>" style="max-height: 300px; overflow-y: auto; scroll-behavior: smooth;">
                                        <?php
                                        if (mysqli_num_rows($message_result) > 0) {
                                            while ($message = mysqli_fetch_assoc($message_result)) {
                                        ?>
                                                <p>
                                                    <strong><?php echo $message['first_name'] . ' ' . $message['last_name']; ?>:</strong>
                                                    <?php echo $message['message_text']; ?>
                                                    <span style="color: gray; font-size: 0.8em;">(<?php echo $message['sent_at']; ?>)</span>
                                                </p>
                                        <?php
                                            }
                                        } else {
                                            echo "<p>No messages yet.</p>";
                                        }
                                        ?>
                                    </div>
                                    <form method="POST">
                                        <input type="hidden" name="hire_id" value="<?php echo $hire_id; ?>">
                                        <textarea name="message_text" placeholder="Type your message here..." required></textarea><br>
                                        <button type="submit" name="send_message">Send</button>
                                    </form>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p>No active chats.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>


            <div id="law-panel">
                <h2>Insert Law</h2>
                <button type="button" id="close-law-form" onclick="toggleInsertLawForm()">Close</button>
                <form method="POST" action="">
                    <label for="law_index">Law Index (ধারা):</label>
                    <input type="text" id="law_index" name="law_index" required>
                    <label for="short_description">Short Description (সংক্ষিপ্ত বর্ণনা):</label>
                    <textarea id="short_description" name="short_description" required></textarea>
                    <label for="full_description">Full Description (সম্পূর্ণ বর্ণনা):</label>
                    <textarea id="full_description" name="full_description" required></textarea>
                    <input type="hidden" name="lawyer_id" value="<?php echo $lawyer['lawyer_id']; ?>">
                    <button type="submit" name="insert_law">Submit</button>
                </form>
            </div>
        </div>
    </div>


    <script>
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

        // Toggle Law Form Panel
        function toggleInsertLawForm() {
            const lawForm = document.getElementById('law-panel');
            if (lawForm.style.display === 'block') {
                lawForm.style.display = 'none';
            } else {
                lawForm.style.display = 'block';
            }
        }
        // Show and hide message panel
        document.getElementById('close-message-panel').addEventListener('click', function() {
            document.getElementById('message-panel').style.display = 'none';
        });

        // Minimize/Maximize chat boxes
        document.querySelectorAll('.minimize-chat').forEach(button => {
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
</body>

</html>