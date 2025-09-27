<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing_page</title>
    <link rel="stylesheet" type="text/css" href="style.css=<?php echo time(); ?>?v">
</head>
<body >
<?php include 'header.php'; ?>
         <!-- Landing Page -->
    <section id="landing-page" style="background-image: url('landing_background.jpg'); height: 100vh; width: 100vw;" >
      <!-- <header>
        <img src="logo.png" alt="Logo" id="logo" />
        <nav>
          <a href="#" id="login-link">Login</a>
          <a href="#" id="signup-link">Sign Up</a>
          <a href="#" id="professional-signup-link" class="professional-button">Be a Professional</a>
        </nav>
      </header> -->

      <main class="center-content">
    <h1>Welcome to LawServices</h1>
    <p>Search for legal information or get professional assistance</p>
    <div class="search-bar">
        <!-- Form should wrap input and button -->
        <form method="post" action="law_page.php">
            <input type="text" name="searchTerm" placeholder="Search law topics..." />
            <button type="submit">Search</button>
        </form>
    </div>
</main>

    </section>
    <?php include 'footer.php'; ?>

</body>
</html>