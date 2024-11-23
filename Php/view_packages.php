<?php
include 'db_connect.php'; // Include the database connection

// Fetch the email from the URL (which was passed from the previous page)
if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);

    // Query to fetch packages associated with the company (travel_data table)
    $query = "SELECT uniq_id, email, package_name, package_type, destination, itinerary, places_covered, travel_date, hotel_details, start_city, airline, price_per_two, price_per_person, sightseeing_details, Initial_Payment, Cancellation_Rules, Date_Change_Rules, image1, image2, image3, image4, image5, image6, image7, image8, image9, image10 FROM travel_data WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    // Check if any packages exist
    if (mysqli_num_rows($result) > 0) {
        $packages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $packages = null;
    }
} else {
    echo "No email provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tour Packages - TravelSaathi</title>
    <link rel="stylesheet" href="../CSS/view_packages.css">
</head>
<body>
<?php include "header_company1.php" ?>
    <div class="container-packages">
        <div class="header-packages">
            <h1>Your Tour Packages</h1>
</div>
        <br>
        <main>
            <h2>Active Packages</h2>
            <br>
            <ul class="package-list">
                <?php if ($packages): ?>
                    <?php foreach ($packages as $package): ?>
                        <li class="listes">
                            <h3><?php echo $package['package_name']; ?> (<?php echo $package['package_type']; ?>) - Destination: <?php echo $package['destination']; ?></h3>
                            <p>Travel Date: <?php echo $package['travel_date']; ?></p>
                            <p>Price for Two: ₹<?php echo $package['price_per_two']; ?></p>
                            <p>Price per Person: ₹<?php echo $package['price_per_person']; ?></p>
                            <p>Duration: <?php echo $package['places_covered']; ?></p>
                            <p>Itinerary: <?php echo $package['itinerary']; ?></p>
                            <p>Hotel Details: <?php echo $package['hotel_details']; ?></p>
                            <p>Start City: <?php echo $package['start_city']; ?></p>
                            <p>Airline: <?php echo $package['airline']; ?></p>
                            <p>Sightseeing Details: <?php echo $package['sightseeing_details']; ?></p>
                            <p>Initial Payment: ₹<?php echo $package['Initial_Payment']; ?></p>
                            <p>Cancellation Rules: <?php echo $package['Cancellation_Rules']; ?></p>
                            <p>Date Change Rules: <?php echo $package['Date_Change_Rules']; ?></p>
                            <p>Images:</p>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <?php if (!empty($package['image' . $i])): ?>
                                    <img src="<?php echo $package['image' . $i]; ?>" alt="<?php echo $package['package_name']; ?>" width="150px">
                                <?php endif; ?>
                            <?php endfor; ?>
                            <a href="edit_package.php?uniq_id=<?php echo $package['uniq_id']; ?>&email=<?php echo urlencode($email); ?>">Edit</a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No active packages found.</li>
                <?php endif; ?>
            </ul>
        </main>
    </div>

    

</body>
</html>

