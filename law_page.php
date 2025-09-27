<?php

include('connection.php');

// Check if the search term is set using either GET or POST method
$searchTerm = isset($_REQUEST['searchTerm']) ? $_REQUEST['searchTerm'] : '';

// Perform the search in the database
if ($searchTerm != '') {
    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM laws WHERE short_description LIKE ? OR full_description LIKE ?");
    $searchTermWildcard = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $searchTermWildcard, $searchTermWildcard);
    $stmt->execute();
    $result = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Law Page</title>
    <link rel="stylesheet" href="law_page.css">
</head>

<body>
    <div class="page-container">

        <header>
            <div class="header-container">
                <img src="logo.png" alt="Logo" id="logo" />
                <nav class="nav-buttons">
                    <a href="user_profile.php">
                        <button>Profile</button>
                    </a>
                    <a href="post.php">
                        <button>Post</button>
                    </a>
                    <a href="<?php echo isset($_SESSION['user_id']) ? 'lawyer_page.php' : 'login.php'; ?>">
                        <button>Professional Help</button>
                    </a>
                </nav>
            </div>
        </header>

        <main>
            <div class="filter-container">
                <form method="POST">
                    <input type="text" name="searchTerm" id="search-text" placeholder="Search by Text" />
                    <button type="submit">Search</button>
                </form>
            </div>

            <div class="law-list">
                <table>
                    <tr>
                        <th>ধারা </th>
                        <th>সংক্ষিপ্ত বিবরণ</th>
                        <th>বিস্তারিত </th>
                    </tr>

                    <?php
                    // Check if the search term has results
                    if ($searchTerm != '') {
                        if (mysqli_num_rows($result) > 0) {
                            // Loop through the rows in the result
                            while ($row = mysqli_fetch_assoc($result)) {
                                if ($row['status'] == 1) {
                                    echo '<tr>';
                                    echo '<td>' . $row['law_index'] . '</td>';
                                    echo '<td>' . $row['short_description'] . '</td>';
                                    echo '<td><button onclick="showDetails(\'law' . $row['law_index'] . '\')">See More</button></td>';
                                    echo '</tr>';
                                } else {
                                    echo '<td>'  . 'Law not Approved Yet' . '</td>';
                                }
                            }
                        } else {
                            echo '<tr><td colspan="3">No results found for "' . $searchTerm . '"</td></tr>';
                        }
                    }
                    ?>
                </table>
            </div>

            <div id="law-details" class="law-details">
                <button onclick="closeDetails()">Close</button>

                <?php
                // Display law details if found
                if ($searchTerm != '') {
                    if (mysqli_num_rows($result) > 0) {
                        mysqli_data_seek($result, 0); // Reset the result pointer
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div id="law' . $row['law_index'] . '-details" class="details-content">';
                            echo '<h2>Law ' . $row['law_index'] . ' Details</h2>';
                            echo '<p>' . $row['full_description'] . '</p>';
                            echo '</div>';
                        }
                    }
                }
                ?>
            </div>
        </main>

        <footer>
            <div class="foot-panel1">A Powerful Shield To Protect You</div>

            <div class="foot-panel2">
                <ul>
                    <h3>Company</h3>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Investor Relations</a></li>
                    <li><a href="#">Notice</a></li>
                    <li><a href="#">Feedback</a></li>
                </ul>

                <ul>
                    <h3>Services</h3>
                    <li><a href="#">Legal Consultation</a></li>
                    <li><a href="#">Case Management</a></li>
                    <li><a href="#">Document Preparation</a></li>
                    <li><a href="#">Corporate Law</a></li>
                    <li><a href="#">Family Law</a></li>
                </ul>

                <ul>
                    <h3>Support</h3>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Legal Disclaimer</a></li>
                </ul>

                <ul>
                    <h3>Follow Us</h3>
                    <li><a href="#"><i class="fab fa-facebook-f"></i> Facebook</a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                    <li><a href="#"><i class="fab fa-linkedin-in"></i> LinkedIn</a></li>
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
            function showDetails(lawId) {
                document.getElementById('law-details').style.right = '0';
                document.querySelectorAll('.details-content').forEach(function(detail) {
                    detail.style.display = 'none';
                });
                document.getElementById(lawId + '-details').style.display = 'block';
            }

            function closeDetails() {
                document.getElementById('law-details').style.right = '-100%';
            }
        </script>
    </div>
</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>