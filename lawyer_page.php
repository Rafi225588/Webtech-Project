<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the user ID and type from the session
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Display the appropriate profile link based on the user type
if ($user_type == 'Lawyer') {
} else {
}

// Include the database connection file
include 'connection.php';

// Retrieve search criteria from the form
$category = isset($_POST['category']) ? $_POST['category'] : '';
$district = isset($_POST['district']) ? $_POST['district'] : '';

// Prepare the SQL query with optional filters
$sql = "SELECT 
    USER.user_id, 
    USER.first_name, 
    USER.last_name, 
    USER.email, 
    USER.profile_picture_url,
    LAWYER.lawyer_id, 
    LAWYER.category, 
    LAWYER.district, 
    LAWYER.experience, 
    LAWYER.rating,
    LAWYER.charge, 
    LAWYER.address, 
    LAWYER.phone, 
    LAWYER.license 
   
FROM USER 
JOIN LAWYER ON USER.user_id = LAWYER.lawyer_id";

$conditions = [];
if (!empty($category)) {
    $conditions[] = "LAWYER.category = '$category'";
}
if (!empty($district)) {
    $conditions[] = "LAWYER.district = '$district'";
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

$result = mysqli_query($conn, $sql);

// Handle the "Hire" button submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hire_lawyer'])) {
    $lawyer_id = intval($_POST['lawyer_id']); // Sanitize lawyer ID
    $user_id = $_SESSION['user_id']; // Get logged-in user's ID

    // Insert a hire request into the Hire table
    $hire_query = "INSERT INTO Hire (user_id, lawyer_id, status) 
                   VALUES ('$user_id', '$lawyer_id', 'Pending')";

    if (mysqli_query($conn, $hire_query)) {
        // Fetch the lawyer's name
        $lawyer_query = "SELECT first_name, last_name FROM USER WHERE user_id = '$lawyer_id'";
        $lawyer_result = mysqli_query($conn, $lawyer_query);
        $lawyer = mysqli_fetch_assoc($lawyer_result);

        // Store a success message in the session
        $_SESSION['message'] = "Hire request sent to lawyer: {$lawyer['first_name']} {$lawyer['last_name']}";
    } else {
        // Store an error message in the session
        $_SESSION['message'] = "Failed to send hire request.";
    }

    // Redirect to the same page to prevent duplicate submissions
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Display any success or error messages
if (isset($_SESSION['message'])) {
    echo "<script>alert('{$_SESSION['message']}');</script>";
    unset($_SESSION['message']); // Clear the message after displaying it
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rating'])) {
    $lawyer_id = intval($_POST['lawyer_id']); // Get lawyer ID
    $user_id = $_SESSION['user_id']; // Logged-in user's ID
    $rating = intval($_POST['rating']); // Rating
    $review_text = trim($_POST['review']); // Review text

    // Validate inputs
    if ($rating >= 1 && $rating <= 5 && !empty($review_text)) {
        // Fetch the hire ID for this user-lawyer combination
        $hire_query = "SELECT hire_id FROM Hire WHERE user_id = ? AND lawyer_id = ? AND status = 'Accepted' ORDER BY hire_date DESC LIMIT 1";
        $stmt_hire = $conn->prepare($hire_query);
        $stmt_hire->bind_param('ii', $user_id, $lawyer_id);
        $stmt_hire->execute();
        $hire_result = $stmt_hire->get_result();

        if ($hire_result->num_rows > 0) {
            $hire_row = $hire_result->fetch_assoc();
            $hire_id = $hire_row['hire_id'];

            // Insert the review into the Review table
            $insert_review_query = "INSERT INTO Review (hire_id, review_text, review_date) VALUES (?, ?, NOW())";
            $stmt_review = $conn->prepare($insert_review_query);
            $stmt_review->bind_param('is', $hire_id, $review_text);

            if ($stmt_review->execute()) {
                // Successfully added the review
                echo "<script>alert('Review submitted successfully!');</script>";

                // Optionally, update the lawyer's rating in the LAWYER table
                $update_rating_query = "UPDATE LAWYER L 
                                        SET L.rating = (
                                            SELECT AVG(LR.rating) 
                                            FROM LAWYER_RATINGS LR 
                                            WHERE LR.lawyer_id = L.lawyer_id
                                        ) 
                                        WHERE L.lawyer_id = ?";
                $stmt_update = $conn->prepare($update_rating_query);
                $stmt_update->bind_param('i', $lawyer_id);
                $stmt_update->execute();
            } else {
                echo "<script>alert('Failed to submit review. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No valid hire found for this lawyer. You can only review after hiring.');</script>";
        }
    } else {
        echo "<script>alert('Invalid rating or review text.');</script>";
    }

    // Redirect to prevent form resubmission
    echo "<script>window.location.href = '" . htmlspecialchars($_SERVER['PHP_SELF']) . "';</script>";
    exit();
}

// Fetch reviews for a specific lawyer
$reviews = [];
if (isset($_POST['lawyer_id'])) {
    $lawyer_id = intval($_POST['lawyer_id']);

    $review_query = "SELECT R.review_text, R.created_at, U.first_name AS reviewer_first_name, U.last_name AS reviewer_last_name
                     FROM Review R
                     JOIN Hire H ON R.hire_id = H.hire_id
                     JOIN USER U ON H.user_id = U.user_id
                     WHERE H.lawyer_id = '$lawyer_id'";

    $review_result = mysqli_query($conn, $review_query);
    while ($row = mysqli_fetch_assoc($review_result)) {
        $reviews[] = $row;
    }
}
$avg_rating_query = "SELECT rating FROM LAWYER WHERE lawyer_id = ?";
$stmt_avg = $conn->prepare($avg_rating_query);
$stmt_avg->bind_param('i', $res['user_id']);
$stmt_avg->execute();
$stmt_avg->bind_result($avg_rating);
$stmt_avg->fetch();
$stmt_avg->close();
?>








<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lawyer Directory</title>
    <link rel="stylesheet" href="Lawyer.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <div class="header-container">
            <img src="logo.png" alt="Logo" id="logo" />
            <div class="header-buttons">
                <?php
                //session_start(); // Start the session

                // Check if the user is logged in
                if (isset($_SESSION['user_id'])) {
                    // Get the user type from the session
                    $user_type = $_SESSION['user_type'];

                    // Display the appropriate profile link based on the user type
                    if ($user_type == 'Lawyer') {
                        echo '<a href="lawyer_profile1.php"><button class="header-button">Profile</button></a>';
                    } else {
                        echo '<a href="user_profile.php"><button class="header-button">Profile</button></a>';
                    }
                } else {
                    // Redirect to login page if not logged in
                    header("Location: login.php");
                    exit();
                }
                ?>
                <a href="post.php"><button class="header-button">Post</button></a>
                <a href="law_page.php"><button class="header-button">Back</button></a>
            </div>
        </div>
    </header>


    <div class="myDesign">
        <form method="POST" action="">
            <select name="category">
                <option value="">Select Category</option>
                <option value="family">পারিবারিক আইনজীবী</option>
                <option value="criminal">অপরাধ আইনজীবী</option>
                <option value="corporate">কর্মকর্তা আইনজীবী</option>
                <option value="property">সম্পত্তি আইনজীবী</option>
                <option value="tax">কর আইনজীবী</option>
                <option value="employment">কর্মসংস্থান আইনজীবী</option>
                <option value="civil">সিভিল আইনজীবী</option>
                <option value="immigration">ইমিগ্রেশন আইনজীবী</option>
                <option value="bankruptcy">দেউলিয়া আইনজীবী</option>
                <option value="intellectual-property">মেধাস্বত্ব আইনজীবী</option>
                <option value="real-estate">রিয়েল এস্টেট আইনজীবী</option>
                <option value="environmental">পরিবেশ আইনজীবী</option>
                <option value="medical-malpractice">চিকিৎসা ত্রুটি আইনজীবী</option>
                <option value="personal-injury">ব্যক্তিগত আঘাত আইনজীবী</option>
                <option value="contract">চুক্তি আইনজীবী</option>
                <option value="international">আন্তর্জাতিক আইনজীবী</option>

                <!-- Add other options -->
            </select>
            <select name="district">
                <option value="">Select District</option>
                <option value="dhaka">ঢাকা</option>
                <option value="chittagong">চট্টগ্রাম</option>
                <option value="khulna">খুলনা</option>
                <!-- Add other options -->
            </select>
            <button type="submit">Search</button>
        </form>
    </div>

    <main>
        <section class="lawyer-list">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php foreach ($result as $res): ?>
                    <div class="FahadCard">
                        <div class="leftF">
                            <div class="topF">
                                <img src="uploads/profile_pictures/<?php echo $res["profile_picture_url"]; ?>" alt="">


                                <div class="info">

                                    <h2><?php echo $res["first_name"]; ?></h2>
                                    <h3><?php echo $res["category"]; ?></h3>
                                    <h4>Rating: <?php echo $res["rating"]; ?></h4>
                                </div>
                            </div>
                            <div class="bottomF">
                                <!-- HIRE Button -->
                                <form method="POST">
                                    <input type="hidden" name="lawyer_id" value="<?php echo $res['user_id']; ?>">
                                    <button type="submit" name="hire_lawyer" class="request-button">HIRE</button>
                                </form>

                                <?php
                                // Fetch the most recent accepted hire request for this lawyer and user
                                $check_hire_query = "SELECT H.hire_id, H.status 
                         FROM Hire H 
                         WHERE H.lawyer_id = ? AND H.user_id = ? AND H.status = 'Accepted' 
                         ORDER BY H.hire_date DESC LIMIT 1";
                                $stmt = $conn->prepare($check_hire_query);
                                $stmt->bind_param('ii', $res['user_id'], $user_id);
                                $stmt->execute();
                                $hire_result = $stmt->get_result();

                                if ($hire_result && $hire_result->num_rows > 0) {
                                    $hire_data = $hire_result->fetch_assoc();
                                    // Show the review form for the most recent hire request
                                ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="lawyer_id" value="<?php echo $res['user_id']; ?>">
                                        <label for="rating">Rating:</label>
                                        <select name="rating" id="rating" required>
                                            <option value="1">Worst</option>
                                            <option value="2">Bad</option>
                                            <option value="3">Avg</option>
                                            <option value="4">Good</option>
                                            <option value="5">Excellent</option>
                                        </select>
                                        <textarea name="review" placeholder="Write your review..." required></textarea>
                                        <button type="submit" name="submit_rating">Submit Rating</button>
                                    </form>
                                <?php
                                }
                                ?>
                            </div>

                        </div>
                        <div class="rightF">
                            <div class="one" style="border-radius:0px 30px 0px 0px">
                                <h2><?php echo $res["experience"]; ?> years</h2>
                                <h4>Experience</h4>
                            </div>
                            <div class="one">
                                <h2>$<?php echo $res["charge"]; ?>/hour</h2>
                                <h4>Charges</h4>
                            </div>
                            <div class="one" style="border-radius:0px 0px 30px 0px">
                                <h2 class="seeMoreBtn" style="font-size:18px; cursor:pointer;" onclick="showDetails('lawyer<?php echo $res['user_id']; ?>')">See More</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Lawyer Details -->
                    <!-- Inside your main lawyer details section -->
                    <div id="lawyer<?php echo $res['user_id']; ?>" class="lawyer-details" style="display: none;">
                        <button class="close-details" onclick="closeDetails('lawyer<?php echo $res['user_id']; ?>')">Close</button>
                        <div class="details-content">
                            <h2><?php echo $res["first_name"] . " " . $res["last_name"]; ?></h2>
                            <p>Address: <?php echo $res["address"]; ?></p>
                            <p>Phone: <?php echo $res["phone"]; ?></p>
                            <p>License: <?php echo $res["license"]; ?></p>
                            <div class="rating">
                                <h4>Average Rating: <?php echo $res['rating']; ?>/5</h4>
                            </div>
                            <!-- Fetch and display reviews for this lawyer -->
                            <div class="reviews">
                                <h3>Reviews:</h3>
                                <?php
                                // Updated query to include reviewer name
                                $reviews_query = "SELECT R.review_text, U.first_name, U.last_name 
                      FROM Review R
                      JOIN Hire H ON R.hire_id = H.hire_id
                      JOIN USER U ON H.user_id = U.user_id
                      WHERE H.lawyer_id = ?";

                                $stmt_reviews = $conn->prepare($reviews_query);
                                $stmt_reviews->bind_param('i', $res['user_id']);
                                $stmt_reviews->execute();
                                $review_result = $stmt_reviews->get_result();

                                if ($review_result->num_rows > 0) {
                                    while ($review = $review_result->fetch_assoc()) {
                                        $reviewer_name = $review['first_name'] . " " . $review['last_name'];
                                        echo "<p><strong> $reviewer_name:</strong> " . htmlspecialchars($review['review_text']) . "</p>";
                                    }
                                } else {
                                    echo "<p>No reviews available.</p>";
                                }
                                ?>
                            </div>

                        </div>
                    </div>


                <?php endforeach; ?>
            <?php else: ?>
                <p>No lawyers found matching your criteria.</p>
            <?php endif; ?>
        </section>



        <footer>
            <div class="foot-panel1">A Powerful Shield To Protect You</div>

            <div class="foot-panel2">
                <!-- Company Section -->
                <ul>
                    <h3>Company</h3>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Investor Relations</a></li>
                    <li><a href="#">Notice</a></li>
                    <li><a href="#">Feedback</a></li>
                </ul>

                <!-- Services Section -->
                <ul>
                    <h3>Services</h3>
                    <li><a href="#">Legal Consultation</a></li>
                    <li><a href="#">Case Management</a></li>
                    <li><a href="#">Document Preparation</a></li>
                    <li><a href="#">Corporate Law</a></li>
                    <li><a href="#">Family Law</a></li>
                </ul>

                <!-- Support Section -->
                <ul>
                    <h3>Support</h3>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Legal Disclaimer</a></li>
                </ul>

                <!-- Social Media Section -->
                <ul>
                    <h3>Follow Us</h3>
                    <li>
                        <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
                    </li>
                    <li>
                        <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                    </li>
                    <li>
                        <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                    </li>
                    <li>
                        <a href="#"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
                    </li>
                </ul>
            </div>

            <div class="foot-panel3">
                <div class="logo"></div>
            </div>

            <div class="foot-panel4">
                <div class="pages">
                    <a>Conditions of Use</a>
                    <a>Privacy Notice</a>
                    <a>Your Ads Privacy Choices</a>
                </div>
                <div class="copyright">
                    © 1996-2024, LawServices.com, Inc. or its affiliates
                </div>
            </div>
        </footer>

        <script>
            function toggleReviewForm(button) {
                // Remove any existing form
                const existingForm = document.querySelector('.dynamic-review-form');
                if (existingForm) {
                    existingForm.remove();
                }

                // Create a new review form
                const reviewForm = document.createElement('div');
                reviewForm.classList.add('dynamic-review-form');
                reviewForm.innerHTML = `
        <h3>Add a Review:</h3>
        <textarea id="new-review" placeholder="Write your review here..."></textarea>
        <button onclick="submitReview('${button.getAttribute('data-lawyer-id')}')">Submit Review</button>
    `;

                // Append the form next to the clicked button
                button.parentNode.appendChild(reviewForm);

                // Add a click listener to close the form when clicking outside
                document.addEventListener('click', closeReviewFormOnOutsideClick, true);
            }

            function closeReviewFormOnOutsideClick(event) {
                const reviewForm = document.querySelector('.dynamic-review-form');
                if (reviewForm && !reviewForm.contains(event.target) && !event.target.classList.contains('review-button')) {
                    reviewForm.remove();
                    document.removeEventListener('click', closeReviewFormOnOutsideClick, true);
                }
            }

            function submitReview(lawyerId) {
                const reviewText = document.getElementById('new-review').value;
                if (reviewText) {
                    alert(`Review for ${lawyerId}: ${reviewText}`);
                    // Optionally, you can append the review somewhere or send it to a server here.

                    // Clear the form
                    document.querySelector('.dynamic-review-form').remove();
                    document.removeEventListener('click', closeReviewFormOnOutsideClick, true);
                } else {
                    alert('Please write a review before submitting!');
                }
            }



            function showDetails(lawyerId) {
                document.getElementById('lawyer-details').style.right = '0';
            }

            function closeDetails() {
                document.getElementById('lawyer-details').style.right = '-100%';
            }

            function rateLawyer(element) {
                const rating = element.getAttribute('data-rating');
                alert('Rated ' + rating + ' stars!');
            }

            // JavaScript to handle dropdown behavior
            // Toggle for Category Dropdown
            function toggleDropdown() {
                document.getElementById("dropdownMenu").classList.toggle("show");
            }

            // Update the input field when an option is selected (Category)
            document.querySelectorAll("#dropdownMenu option").forEach(function(option) {
                option.addEventListener("click", function() {
                    document.getElementById("categoryInput").value = this.textContent;
                    document.getElementById("dropdownMenu").classList.remove("show");
                });
            });

            // Show the Category dropdown when the input is clicked
            document.getElementById("categoryInput").addEventListener("click", function() {
                toggleDropdown();
            });

            // Toggle for District Dropdown
            function toggleDistrictDropdown() {
                document.getElementById("districtDropdownMenu").classList.toggle("show");
            }

            // Update the input field when a district option is selected
            document.querySelectorAll("#districtDropdownMenu option").forEach(function(option) {
                option.addEventListener("click", function() {
                    document.getElementById("districtInput").value = this.textContent;
                    document.getElementById("districtDropdownMenu").classList.remove("show");
                });
            });

            // Show the District dropdown when the input is clicked
            document.getElementById("districtInput").addEventListener("click", function() {
                toggleDistrictDropdown();
            });

            function showDetails(lawyerId) {
                const details = document.getElementById(lawyerId);
                if (details) {
                    details.style.display = 'block'; // Show the details section
                    details.style.right = '0'; // Slide in from the right
                }
            }

            function closeDetails(lawyerId) {
                const details = document.getElementById(lawyerId);
                if (details) {
                    details.style.right = '-100%'; // Slide out to the right
                    setTimeout(() => {
                        details.style.display = 'none'; // Hide after sliding out
                    }, 300); // Match the transition duration
                }
            }
        </script>
</body>

</html>

<?php
$conn->close();
?>