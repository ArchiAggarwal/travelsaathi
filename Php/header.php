<?php
session_start();

// Check if the email is set in the URL and not in the session
if (isset($_GET['email']) && !isset($_SESSION['user_email'])) {
    $_SESSION['user_email'] = $_GET['email'];
    $_SESSION['loggedin'] = true;  // Set logged-in status to true for testing
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <title>TravelSaathi</title>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <!-- Logo Area -->
        <div class="logo">
            <img src="images/Final LOGO(1)(1).png" alt="TravelSaathi Logo">
        </div>

        <!-- Navigation Links -->
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="my_trips.php">My Trips</a>
            <a href="listed_packages.php">Search</a>
            <a href="aboutus.php">About Us</a>
            <a href="contactus.php">Contact Us</a>
            <a href="wishlist.php">
                <i class="far fa-heart"></i> WishList
            </a>
        </nav>

        <!-- Conditional User Display -->
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <!-- Display User Email and Profile Picture -->
            <div class="user-container">
                <img src="images/1.jpg" alt="User Profile Picture" class="user-profile-pic">
                <span><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                <a href="logout.php" class="sign-in-btn hollow-btn">Logout</a> 
            </div>
        <?php else: ?>
            <!-- Sign In and Log In Buttons -->
            <div class="auth-buttons">
                <a href="signup.php" class="sign-in-btn hollow-btn">Sign In</a>
                <a href="login.php" class="sign-in-btn hollow-btn">Log In</a> 
            </div>
        <?php endif; ?>
    </header>
</body>
</html>



