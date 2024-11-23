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
    <title>Tour Company Profile - TravelSaathi</title>
    <link rel="stylesheet" href="../CSS/profile.css">
</head>
<body>
<?php include "header_company.php"; ?>
    <div class="container-profile">
        <br>
        <main>
            <section class="profile">
                <h2>Your Company Profile</h2>
                <?php if ($company): ?>
                    <p>Company Name: <strong><?php echo $company['company_name']; ?></strong></p>
                    <p>Company Description: <strong><?php echo $company['description']; ?></strong></p>
                    <p>Contact Information: <strong><?php echo $company['contact_information']; ?></strong></p>
                    <p>Company Logo:</p>
                    <img src="<?php echo $company['logo_path']; ?>" alt="Company Logo" style="max-width: 150px;">
                    <br><br>
                    <!-- Edit Profile Button -->
                    <a href="edit_profile.php?email=<?php echo $email; ?>" class="button">Edit Profile</a>
                <?php else: ?>
                    <p>No company information found.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>

