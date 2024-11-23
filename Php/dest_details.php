<?php
// Database connection
include "db_connect.php";

// Capture uniq_id from URL
$uniq_id = isset($_GET['uniq_id']) ? $_GET['uniq_id'] : null;

if ($uniq_id) {
    // Fetch package details using uniq_id
    // Updated SQL Query to include price_per_person and price_per_two
$sql = "SELECT package_name, itinerary, places_covered, hotel_details, sightseeing_details, Cancellation_Rules, Date_Change_Rules,
image1, image2, image3, image4, image5, image6, image7, image8, image9, image10, 
price_per_person, price_per_two
FROM travel_data WHERE uniq_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uniq_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Prepare itinerary content with day subheadings
        $itineraryContent = '';
        $itineraryArray = explode("\n", $row['itinerary']);
        foreach ($itineraryArray as $line) {
            if (strpos($line, "Day") !== false) {
                $itineraryContent .= "<br><h3>$line</h3>";
            } else {
                $itineraryContent .= "<p>$line</p>";
            }
        }

        // Images
        $imageContent = '';
        for ($i = 1; $i <= 10; $i++) {
            $imageCol = 'image' . $i;
            if (!empty($row[$imageCol])) {
                $imageContent .= "<img src='{$row[$imageCol]}' alt='Image $i'>";
            }
        }
    } else {
        echo "<p>Package not found.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Invalid package ID.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serene Bali</title>
 
</head>
<body>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($row['package_name']) ?> Package</title>
    <link rel="stylesheet" href="styles.css"> 
    <link rel="stylesheet" href="../CSS/dest_details.css">
    <script src="../Javascript/dest_details.js"></script>

</head>
<body>
<section>
        <?php include "header.php"; ?>
</section>
<div class="container">
    <!-- Left Section: Main Content -->
    <div class="left-section">
        <!-- Destination Heading -->
        <div class="heading"><?= htmlspecialchars($row['package_name']) ?></div>

        <!-- Image Gallery -->
        <div class="gallery">
            <?= $imageContent ?>
        </div>
        <br><br>
        
        <!-- Tabs: Itinerary, Policies -->
        <div class="tabs">
            <button class="active" data-tab="itinerary">Itinerary</button>
            <button data-tab="policies">Policies</button>
            <button data-tab="summary">Summary</button>
        </div>

        <!-- Itinerary Content -->
        <div id="itinerary" class="tab-content active">
            <div class="details">
                <?= $itineraryContent ?>
            </div>
        </div>

        <!-- Policies Content -->
        <div id="policies" class="tab-content">
            <div class="details">
                <h3>Cancellation Policy</h3>
                <p><?= htmlspecialchars($row['Cancellation_Rules']) ?></p><br>
                <h3>Date Change Rules</h3>
                <p><?= htmlspecialchars($row['Date_Change_Rules']) ?></p>
            </div>
        </div>

        <!-- Summary Content -->
<div id="summary" class="tab-content">
    <div class="details">
        <h3>Places Covered</h3>
        <?php 
            $placesCovered = explode('.', $row['places_covered']);
            foreach ($placesCovered as $sentence) {
                if (trim($sentence)) {
                    echo "<p>" . htmlspecialchars(trim($sentence)) . ".</p>";
                }
            }
        ?><br>

        <h3>Hotel Details</h3>
        <?php 
            $hotelDetails = explode('.', $row['hotel_details']);
            foreach ($hotelDetails as $sentence) {
                if (trim($sentence)) {
                    echo "<p>" . htmlspecialchars(trim($sentence)) . ".</p>";
                }
            }
        ?><br>

        <h3>Sightseeing Details</h3>
        <?php 
            $sightseeingDetails = explode('.', $row['sightseeing_details']);
            foreach ($sightseeingDetails as $sentence) {
                if (trim($sentence)) {
                    echo "<p>" . htmlspecialchars(trim($sentence)) . ".</p>";
                }
            }
        ?>
    </div>
</div>


    <!-- Right Section: Payment and Offer Details -->
    <div class="right-section">
        <div class="price-box">₹<?= htmlspecialchars($row['price_per_person']) ?>/Person</div>
        <div class="discount">12% OFF</div>
        <a href="checkout.php?uniq_id=<?php echo $uniq_id; ?>">
    <button type="button" class="payment-button">Checkout</button>
</a>
        <div class="coupon-box">
            <div class="coupon-title">Coupons & Offers</div>
            <div class="coupon"><strong>GRANDOFFER</strong> - ₹5,817 Off</div>
            <div class="coupon"><strong>FEDERALEMI</strong> - ₹8,554 Off</div>
        </div>
    </div>
</div>




</body>
</html>


