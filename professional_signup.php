<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "LAW_PROJECT";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$error_message = ''; // Initialize variable for error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $phone = sanitizeInput($_POST['phone']);
    $email = sanitizeInput($_POST['email']);
    $nid = sanitizeInput($_POST['nid']);
    $license = sanitizeInput($_POST['license']);
    $category = sanitizeInput($_POST['category']);
    $district = sanitizeInput($_POST['district']); // Get district value
    $user_type = 'Lawyer'; // Fixed user type for this form
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if NID or License already exists
    if (empty($error_message)) {
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM LAWYER WHERE nid = ? OR license = ?");
        $stmt_check->bind_param("ss", $nid, $license);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();

        if ($count > 0) {
            $error_message = "NID or License is already in use. Please use a valid one.";
        }
        $stmt_check->close();
    }

    // If no error, proceed with insertion
    if (empty($error_message)) {
        // Insert into USER table
        $stmt_user = $conn->prepare("INSERT INTO USER (first_name, last_name, email, user_type, password) VALUES (?, ?, ?, ?, ?)");
        $stmt_user->bind_param("sssss", $first_name, $last_name, $email, $user_type, $hashed_password);

        if ($stmt_user->execute()) {
            $user_id = $conn->insert_id; // Get the last inserted user ID

            // Insert into LAWYER table
            $stmt_lawyer = $conn->prepare("INSERT INTO LAWYER (lawyer_id, phone, nid, license, category, district) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_lawyer->bind_param("isssss", $user_id, $phone, $nid, $license, $category, $district);

            if ($stmt_lawyer->execute()) {
                echo "<script>alert('Professional signup successful!'); window.location.href='login.php';</script>";
            } else {
                // Display more detailed error message
                echo "Error inserting into LAWYER table: " . $stmt_lawyer->error;
            }

            $stmt_lawyer->close();
        } else {
            echo "Error inserting into USER table: " . $stmt_user->error;
        }

        $stmt_user->close();
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
    <title>Professional Signup</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            let phone = document.forms["signupForm"]["phone"].value;
            let password = document.forms["signupForm"]["password"].value;
            let confirmPassword = document.forms["signupForm"]["confirm_password"].value;

            if (!/^\d{11}$/.test(phone)) {
                alert("Phone number must be 11 digits.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <?php 
    include 'header.php';
    ?>
     
    <!-- Professional Signup Page -->
    <section id="professional-signup-page" style="background-image: url('professional_background.jpg'); background-size: cover; background-position: center; background-attachment: fixed; height: 100vh; width: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column; margin: 0; padding: 0; color: white;">
    

        <main>
          <h1>Sign Up as a Professional (Lawyer)</h1>
          <form name="signupForm" method="POST" action="" onsubmit="return validateForm()">
            <input type="text" placeholder="First Name" name="first_name" value="<?php echo isset($first_name) ? $first_name : ''; ?>" required />
            <input type="text" placeholder="Last Name" name="last_name" value="<?php echo isset($last_name) ? $last_name : ''; ?>" required />
            <input type="text" placeholder="Phone Number" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" required />
            <input type="email" placeholder="Email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required />
            <div class="search-container">
                <div class="dropdown">
                    <input type="text" id="categoryInput" placeholder="বিভাগ নির্বাচন করুন..." readonly onclick="toggleDropdown('dropdownMenu')" value="<?php echo isset($category) ? $category : ''; ?>">
                    <span class="dropdown-arrow">&#9662;</span>
                    <div id="dropdownMenu" class="dropdown-menu">
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
                    </div>
                    <input type="hidden" id="categoryValue" name="category" value="<?php echo isset($category) ? $category : ''; ?>">
                </div>
                <div class="dropdown">
    <input type="text" id="districtInput" placeholder="জেলা নির্বাচন করুন..." readonly onclick="toggleDropdown('districtMenu')" value="<?php echo isset($district) ? $district : ''; ?>">
    <span class="dropdown-arrow">&#9662;</span>
    <div id="districtMenu" class="dropdown-menu">
        <option value="dhaka">ঢাকা</option>
        <option value="chittagong">চট্টগ্রাম</option>
        <option value="khulna">খুলনা</option>
    </div>
    <input type="hidden" id="districtValue" name="district" value="<?php echo isset($district) ? $district : ''; ?>">
</div>
            </div>
            <input type="text" placeholder="NID Details" name="nid" value="<?php echo isset($nid) ? $nid : ''; ?>" required />
            <input type="text" placeholder="License Number" name="license" value="<?php echo isset($license) ? $license : ''; ?>" required />
            <input type="text" placeholder="UserType" value="Lawyer" name="user" readonly required />
            <input type="password" placeholder="Password" name="password" required />
            <input type="password" placeholder="Confirm Password" name="confirm_password" required />
            <button type="submit" id="professional-signup-btn">Sign Up as Professional</button>
          </form>

          <!-- Show Error Message -->
          <?php if ($error_message != ''): ?>
            <div class="error-message" style="color: red; margin-top: 10px;">
                <?php echo $error_message; ?>
            </div>
          <?php endif; ?>
        </main>
    </section>
    <?php include 'footer.php'; ?>
    <script>
    function toggleDropdown(menuId) {
        document.getElementById(menuId).classList.toggle("show");
    }

    function handleDropdownSelection(menuId, inputId, valueId) {
        document.getElementById(menuId).addEventListener("click", function(event) {
            let selectedOption = event.target;
            document.getElementById(inputId).value = selectedOption.innerText;
            document.getElementById(valueId).value = selectedOption.value;
            document.getElementById(menuId).classList.remove("show");
        });
    }

    // Initialize event listeners for both dropdowns
    handleDropdownSelection("dropdownMenu", "categoryInput", "categoryValue");
    handleDropdownSelection("districtMenu", "districtInput", "districtValue");

    // Close the dropdown if clicked outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown input')) {
            var dropdowns = document.getElementsByClassName('dropdown-menu');
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    };
</script>

</body>
</html>
