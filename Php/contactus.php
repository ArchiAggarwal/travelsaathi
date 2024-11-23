<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Contact Us</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../CSS/contactus.css">
</head>
<body>
<?php
include "header.php";

include "db_connect.php";

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    

    // Insert data into `contact_form` table (replace with your table name)
    $sql = "INSERT INTO contact_form (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
    } else {
        echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }

    // Close the connection
    $conn->close();
}
?>

    <div class="contact-container">
        <div class="contact-info">
            <h1>Contact Us</h1>
            <p>We’d love to hear from you! Please fill out the form below or reach out to us at:</p>
            <ul><br>
                <li> <i class="fa fa-phone" style="color: green;"></i> <strong>Phone:</strong> +123 456 789</li><br>
                <li><i class="fa fa-envelope" style="color: rgb(248, 248, 248);"></i> <strong>Email:</strong>support@travelsaathi.com</li><br>
                <li> <i class="fa fa-location-dot" style="color: rgb(105, 84, 210);"></i> <strong>Address:</strong> 123 Street, City, Country</li>
            </ul>
        </div>

        <div class="contact-form">
            <h2>Get in Touch</h2>
            <form action="#" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

<?php
include "footer.php";
?>
</body>
</html>



