<?php
include 'db_connect.php';  // Include your database connection

// Fetch the email from the URL
if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);

    // Query to fetch company information based on email
    $query = "SELECT company_name, description, contact_information, logo_path FROM company_login WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    // Check if the company exists
    if (mysqli_num_rows($result) > 0) {
        $company = mysqli_fetch_assoc($result);
    } else {
        $company = null; // No company found
    }
} else {
    // Redirect or handle the case where email is not set
    header("Location: error.php"); // Redirect to an error page or handle it
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Company Dashboard - TravelSaathi</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="../CSS/header_company.css">
    
</head>
<body>
    <div class="container-header">
        <header>
            <br>
            <h1>Welcome to Your Dashboard, <?php echo $company ? $company['company_name'] : 'Your Company'; ?>!</h1>
            <br>
            <nav>
                <ul>
                <li><a href="profile.php?email=<?php echo $email; ?>">Profile</a></li>
                <li><a href="upload_package.php?email=<?php echo $email; ?>">Upload Tour Packages</a></li>
                <li><a href="view_packages.php?email=<?php echo $email; ?>">View Tour Packages</a></li>
                <li><a href="view_bookings.php?email=<?php echo $email; ?>">View Bookings</a></li>
                <li><a href="discount.php?email=<?php echo $email; ?>">Discounts</a></li>
                <li><a href="change_password.php?email=<?php echo $email; ?>">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
</body>
</html>        
