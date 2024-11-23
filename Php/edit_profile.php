<?php
include 'db_connect.php';  // Include your database connection
include "header_company.php";
// Fetch the email from the URL
if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);

    // Fetch the company details
    $query = "SELECT * FROM company_login WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $company = mysqli_fetch_assoc($result);
} else {
    header("Location: error.php");
    exit();
}

// Handle form submission for updating the profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = $_POST['company_name'];
    $description = $_POST['description'];
    $contact_information = $_POST['contact_information'];

    // Check if a new logo was uploaded
    if ($_FILES['logo']['name']) {
        $logo_path = 'uploads/' . basename($_FILES['logo']['name']);
        move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path);
    } else {
        $logo_path = $company['logo_path'];  // Keep the existing logo if no new file is uploaded
    }

    // Update query
    $updateQuery = "UPDATE company_login SET company_name='$company_name', description='$description', contact_information='$contact_information', logo_path='$logo_path' WHERE email='$email'";
    if (mysqli_query($conn, $updateQuery)) {
        header("Location: tour_dashboard.php?email=$email");  // Redirect back to dashboard
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../CSS/edit_profile.css">
</head>
<body>
    <h2>Edit Company Profile</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company['company_name']); ?>" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" ><?php echo htmlspecialchars($company['description']); ?></textarea><br>

        <label for="contact_information">Contact Information:</label>
        <input type="text" id="contact_information" name="contact_information" value="<?php echo htmlspecialchars($company['contact_information']); ?>"><br>

        <label for="logo">Current Logo:</label><br>
        <img src="<?php echo htmlspecialchars($company['logo_path']); ?>" alt="Current Logo" style="max-width: 150px; margin-bottom: 10px;"><br>

        <label for="logo">Upload New Logo (optional):</label>
        <input type="file" id="logo" name="logo" accept="image/*"><br>

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
