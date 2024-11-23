<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="../CSS/footer.csss">
    <title>TravelSaathi Footer</title>
</head>
<body>
<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        include "db_connect.php";

        // Insert email into the database
        $sql = "INSERT INTO newsletter_subscribers (email) VALUES ('$email')";

        if ($conn->query($sql) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
        } else {
            if ($conn->errno == 1062) { // Duplicate entry error
                echo "This email is already subscribed.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Close the connection
        $conn->close();
    } else {
        echo "Invalid email address.";
    }
}
?>

<div class="footer-top">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-6">
                <h6>Explore</h6>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="aboutus.php">About Us</a></li>
                    <li><a href="listed_packages.php">Destinations</a></li>
                    <li><a href="travel_guide.php">Travel Guides</a></li>
                    <li><a href="discount_web.php">Discounts</a></li>
                </ul>
            </div>

            <div class="col-md-4 col-6">
                <h6>Support</h6>
                <ul>
                    <li><a href="contactus.php">Contact Us</a></li>
                    <li><a href="booking_policy.php">Booking Policy</a></li>
                    <li><a href="faqs.php">FAQs</a></li>
                    <li><a href="privacy_policy.php">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="col-md-4 col-6">
                <h6>Follow Us</h6>
                <ul>
                    <li><a href="">Facebook</a></li>
                    <li><a href="https://www.instagram.com/travelsaathi2024/">Instagram</a></li>
                    <li><a href="https://x.com/TravelSaathi24">X</a></li>
                    <li><a href="https://www.linkedin.com/in/travel-saathi-bb0463336/">LinkedIn</a></li>
                </ul>
            </div>
        </div>

        <div class="subscribe-section">
            <h5>Subscribe to Our Newsletter</h5>
            <form id="newsletter-form" action="newsletter_subscribe.php" method="POST">
    <input type="email" id="email-input" name="email" class="form-control" placeholder="Enter your email" required>
    <button type="submit" class="btn-subscribe">Subscribe</button>
</form>
        </div>

        <div class="row">
            <div class="col-12 text-center mt-3">
                <p>&copy; 2024 TravelSaathi.com | All Rights Reserved</p>
            </div>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
