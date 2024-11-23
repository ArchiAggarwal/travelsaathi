<?php
include 'db_connect.php';
include 'header.php';

// Ensure the user is logged in, and get their email
$userEmail = $_SESSION['user_email'] ?? '';
if (empty($userEmail)) {
    echo "<p>Please log in to view your wishlist.</p>";
    exit();
}

// SQL query to fetch liked packages for the user
$sql = "
SELECT td.uniq_id, td.image1, td.start_city, td.destination, td.duration, td.airline, td.package_type, td.itinerary, td.price_per_person, td.sightseeing_details
FROM travel_data td
JOIN favorites f ON td.uniq_id = f.uniq_id
WHERE f.email = ?
";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Bind the email parameter and execute the query
$stmt->bind_param('s', $userEmail);

if (!$stmt->execute()) {
    echo "Error executing query: " . $stmt->error;
    exit();
} else {
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../CSS/wishlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../Javascript/wishlist.js"></script>

</head>
<body>
    
    <h1>My Wishlist</h1>
    
    <div class="wishlist-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Generate the URL to the dest_details.php page with uniq_id
                $details_url = "dest_details.php?uniq_id=" . urlencode($row['uniq_id']);
                ?>
                <div class="wishlist-card" data-uniq-id="<?php echo htmlspecialchars($row['uniq_id']); ?>">
                    <a href="<?php echo $details_url; ?>">
                        <img src="<?php echo htmlspecialchars($row['image1']); ?>" alt="Package Image" class="package-card-img">
                    </a>
                    <div class="heart-icon active" onclick="toggleWishlist(this)">
                        <i class="fas fa-heart"></i> <!-- Liked items show as solid red heart -->
                    </div>
                    <h3><?php echo htmlspecialchars($row['start_city']) . " to " . htmlspecialchars($row['destination']); ?></h3>
                    <p><?php echo htmlspecialchars($row['duration']); ?> Nights</p>
                    <p><?php echo htmlspecialchars($row['airline']); ?></p>
                    <p><?php echo htmlspecialchars($row['package_type']); ?></p>
                    <p class="sight"><?php echo htmlspecialchars($row['sightseeing_details']); ?></p>
                    <p class="price-per-person">
                        <?php
                        $pricePerPerson = number_format($row['price_per_person']);
                        echo "₹" . $pricePerPerson . " / Person";
                        ?>
                    </p>
                </div>
                <?php
            }
        } else {
            echo "<p>No packages found in your wishlist.</p>";
        }
        ?>
    </div>

</body>
</html>


<?php
// Close the statement and the connection
$stmt->close();
$conn->close();
?>