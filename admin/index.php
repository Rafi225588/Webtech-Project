<?php
// Start session to ensure only logged-in users can access the page
session_start();

if (!isset($_SESSION['admin_id'])) {
    // If no session is found, redirect to admin login page
    header("Location: adminLogin.php");
    exit;
}

// Add logout functionality
if (isset($_POST['logout'])) {
    // Destroy session and redirect to login page
    session_destroy();
    header("Location: adminLogin.php");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "LAW_PROJECT";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle status updates via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $law_id = filter_input(INPUT_POST, 'law_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if (!$law_id || !in_array($action, ['approve', 'reject'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid parameters.']);
        exit;
    }

    $status = ($action === 'approve') ? 1 : 0;

    // Begin transaction for multiple updates
    $conn->begin_transaction();

    try {
        // Debug point: Log the status and law_id
        error_log("Updating law_id: $law_id with status: $status");

        // Update law status
        $stmt = $conn->prepare("UPDATE `laws` SET `status` = ? WHERE `law_id` = ?");
        $stmt->bind_param("ii", $status, $law_id);
        if (!$stmt->execute()) {
            throw new Exception('Failed to execute law status update.');
        }

        // Additional debugging
        error_log("Law status updated successfully for law_id: $law_id");

        if ($status === 1) {
            // Get the lawyer_id for this law
            $stmt = $conn->prepare("SELECT `lawyer_id` FROM `laws` WHERE `law_id` = ?");
            $stmt->bind_param("i", $law_id);
            $stmt->execute();
            $stmt->bind_result($lawyer_id);
            $stmt->fetch();
            $stmt->close();

            // Debug point: Log the lawyer_id
            error_log("Fetched lawyer_id: $lawyer_id for law_id: $law_id");

            if ($lawyer_id) {
                // Add 5 stars for the lawyer when the law is approved
                $stmt = $conn->prepare("INSERT INTO `LAWYER_RATINGS` (`lawyer_id`, `user_id`, `rating`, `created_at`) 
                                        VALUES (?, ?, 4, NOW()) 
                                        ON DUPLICATE KEY UPDATE `rating` = `rating` + 5, `created_at` = NOW()");
                $stmt->bind_param("ii", $lawyer_id, $lawyer_id);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to update lawyer rating.');
                }

                // Recalculate and update the rating in the LAWYER table
                $stmt_avg = $conn->prepare("SELECT AVG(rating) FROM LAWYER_RATINGS WHERE lawyer_id = ?");
                $stmt_avg->bind_param("i", $lawyer_id);
                $stmt_avg->execute();
                $stmt_avg->bind_result($average_rating);
                $stmt_avg->fetch();
                $stmt_avg->close();

                // Update the lawyer's rating in the LAWYER table
                $stmt_update_rating = $conn->prepare("UPDATE LAWYER SET rating = ? WHERE lawyer_id = ?");
                $stmt_update_rating->bind_param("di", $average_rating, $lawyer_id);
                if (!$stmt_update_rating->execute()) {
                    throw new Exception('Failed to update lawyer rating in LAWYER table.');
                }
            }
        }

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Transaction failed: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    exit;
}

// Fetch laws with null status
$query = "SELECT * FROM laws WHERE status IS NULL ORDER BY created_at DESC";
$result = $conn->query($query);

$laws = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Laws</title>
    <link rel="stylesheet" href="index.css?<?php echo time(); ?>">
    <script>
        async function updateLawStatus(lawId, action) {
            try {
                if (!['approve', 'reject'].includes(action)) {
                    alert('Invalid action type');
                    return;
                }

                const formData = new FormData();
                formData.append('law_id', lawId);
                formData.append('action', action);

                const response = await fetch('index.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (response.ok) {
                    if (result.success) {
                        const row = document.getElementById(`law-row-${lawId}`);
                        if (row) row.remove();
                        alert(`Law ${action}d successfully.`);
                    } else {
                        console.error('Server error:', result.error);
                        alert(`Error: ${result.error}`);
                    }
                } else {
                    console.error('HTTP error:', response.statusText);
                    alert(`HTTP Error: ${response.statusText}`);
                }
            } catch (error) {
                console.error('Unexpected error:', error);
                alert('An unexpected error occurred. Please try again.');
            }
        }
    </script>
    <style>
        .thisoutbtn {
            background: red;
            color: white;
            padding: 10px 20px;
        }

        .thisoutbtn:hover {
            background: rgb(205, 12, 2);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Pending Laws</h1>

        <!-- Add Logout Button -->
        <form method="POST" action="">
            <button class="thisoutbtn" type="submit" name="logout" class="btn">Logout</button>
        </form>

        <?php if (empty($laws)): ?>
            <p>No pending laws to review.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Law ID</th>
                        <th>Index</th>
                        <th>Short Description</th>
                        <th>Full Description</th>
                        <th>Created At</th>
                        <th>Lawyer ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laws as $law): ?>
                        <tr id="law-row-<?= htmlspecialchars($law['law_id']); ?>">
                            <td><?= htmlspecialchars($law['law_id']); ?></td>
                            <td><?= htmlspecialchars($law['law_index']); ?></td>
                            <td><?= htmlspecialchars($law['short_description']); ?></td>
                            <td><?= htmlspecialchars($law['full_description']); ?></td>
                            <td><?= htmlspecialchars($law['created_at']); ?></td>
                            <td><?= htmlspecialchars($law['lawyer_id']); ?></td>
                            <td>
                                <button onclick="updateLawStatus(<?= htmlspecialchars($law['law_id']); ?>, 'approve')">Approve</button>
                                <button onclick="updateLawStatus(<?= htmlspecialchars($law['law_id']); ?>, 'reject')">Reject</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>
