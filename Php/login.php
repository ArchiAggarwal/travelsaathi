<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<?php
include 'db_connect.php';  // Include your database connection

// Handle Login
if (isset($_POST['email']) && isset($_POST['login_type'])) {  
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $login_type = $_POST['login_type'];  // Get the selected login type

    // Define table and redirect page based on login type
    if ($login_type == 'ADMIN') {
        $table = 'admin_login';
        $redirect_page = 'admin_dashboard.php';
    } elseif ($login_type == 'TOUR COMPANY') {
        $table = 'company_login';
        $redirect_page = 'profile.php';
    } elseif ($login_type == 'TOURIST') {
        $table = 'user_login';
        $redirect_page = 'my_trips.php';
    } elseif ($login_type == 'BLOGS & VLOGS') {
        $table = 'blog_vlog_login';
        $redirect_page = 'blogs&vlogs_dashboard.php';
    } else {
        $message = "Invalid login type!";
    }

    // If a valid table is set, continue with the query
    if (isset($table)) {
        // Fetch the user from the respective table
        $query = "SELECT * FROM $table WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['loggedin'] = true; // Set to true when the user is logged in
                $_SESSION['user_email'] = $email; // Store the user's email

                // Redirect based on login type
                header("Location: $redirect_page?email=" . urlencode($email));
                exit();
            } else {
                $message = "Invalid email or password!";
            }
        } else {
            $message = "User not found!";
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

<!-- Login Container -->
<div class="container1" id="login">
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="  Email" required>
        <input type="password" name="password" placeholder="  Password" required>
        <select id="login_type" name="login_type" required>
    <option value="ADMIN">ADMIN</option>
    <option value="TOUR COMPANY">TOUR COMPANY</option>
    <option value="TOURIST">TOURIST</option>
    <option value="BLOGS & VLOGS">BLOGS & VLOGS</option>
</select>
        <button type="submit">Log In</button>
    </form>

    <div class="link1">
        Don't have an account? <a href="signup.php">Sign Up</a>
    </div>
</div>

<?php include "footer.php"; ?>
</body>

</html>

