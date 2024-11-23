<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../CSS/signup.css">
    <script src="../Javascript/signup.js"></script>

</head>
<?php
include 'db_connect.php';  // Include your database connection

// Handle Signup
if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hashing the password for security
    $role = mysqli_real_escape_string($conn, $_POST['role']);  // Fetch the selected role

    // Check if the email already exists in all four tables
    $query_admin = "SELECT * FROM admin_login WHERE email = '$email'";
    $query_company = "SELECT * FROM company_login WHERE email = '$email'";
    $query_user = "SELECT * FROM user_login WHERE email = '$email'";
    $query_blogs_vlogs = "SELECT * FROM blog_vlog_login WHERE email = '$email'"; // New query for blogs and vlogs

    $result_admin = mysqli_query($conn, $query_admin);
    $result_company = mysqli_query($conn, $query_company);
    $result_user = mysqli_query($conn, $query_user);
    $result_blogs_vlogs = mysqli_query($conn, $query_blogs_vlogs); // New result for blogs and vlogs

    if (mysqli_num_rows($result_admin) > 0 || mysqli_num_rows($result_company) > 0 || mysqli_num_rows($result_user) > 0 || mysqli_num_rows($result_blogs_vlogs) > 0) {
        $message = "Email already exists!";
    } else {
        // Insert the user into the appropriate table based on the role
        if ($role == 'admin') {
            $sql = "INSERT INTO admin_login (full_name, email, password) VALUES ('$username', '$email', '$password')";
        } elseif ($role == 'tour_company') {
            // Additional fields for company registration
            $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
            $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $contact_information = mysqli_real_escape_string($conn, $_POST['contact_information']);

            // Handle logo upload
            $logo_path = "";
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
                $logo_name = $_FILES['logo']['name'];
                $logo_tmp = $_FILES['logo']['tmp_name'];
                $upload_dir = "uploads/";
                $logo_path = $upload_dir . uniqid() . "_" . basename($logo_name);

                // Create the upload directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Move the uploaded file to the designated folder
                if (move_uploaded_file($logo_tmp, $logo_path)) {
                    $message = "Logo uploaded successfully!";
                } else {
                    $message = "Failed to upload logo.";
                }
            }

            $sql = "INSERT INTO company_login (company_name, email, password, contact_number, description, contact_information, logo_path) 
                    VALUES ('$company_name', '$email', '$password', '$contact_number', '$description', '$contact_information', '$logo_path')";
        } elseif ($role == 'tourist') {
            $sql = "INSERT INTO user_login (full_name, email, password) VALUES ('$username', '$email', '$password')";
        } elseif ($role == 'blogs_and_vlogs') { // New role handling
            $sql = "INSERT INTO blog_vlog_login (full_name, email, password) VALUES ('$username', '$email', '$password')";
        }

        if (mysqli_query($conn, $sql)) {
            $message = "Signup successful!";
            // Redirect to login page
            header("Location: login.php");
            exit();  // Ensure the script stops after redirection
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

?>


<body>
<?php include "header.php"; ?>

<?php
// Display any message if available
if (isset($message)) {
    echo "<p>$message</p>";
}
?>
<!-- Signup Container -->
<div class="container1" id="signup">
    <h2>Sign Up</h2>
    <!-- Add enctype attribute for file uploads -->
    <form action="signup.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="username" placeholder="  Username" required>
        <input type="email" name="email" placeholder="  Email" required>
        <input type="password" name="password" placeholder="  Password" required>
        <select name="role" id="role" required onchange="toggleCompanyFields()">
    <option value="admin">Admin</option>
    <option value="tour_company">Tour Company</option>
    <option value="tourist">Tourist</option>
    <option value="blogs_and_vlogs">Blogs and Vlogs</option> <!-- New option -->
</select>


        <!-- Additional fields for Tour Company -->
        <div id="company-fields" style="display:none;">
            <input type="text" name="company_name" placeholder="  Company Name">
            <input type="text" name="contact_number" placeholder="  Contact Number">
            <input type="text" name="description" placeholder="  Description">
            <input type="text" name="contact_information" placeholder="  Contact Information">
            
            <!-- Logo upload input -->
            <label class="upload-label">Upload Logo:</label>
            <input type="file" name="logo" accept="image/*" class="file-input">
        </div>

        <button type="submit">Sign Up</button>
    </form>

    <div class="link1">
        Already have an account? <a href="login.php">Log In</a>
    </div>
</div>
<?php include "footer.php"; ?>


</body>

</html>


