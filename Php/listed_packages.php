<?php
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
?>

<?php
include 'db_connect.php';
include "header.php";

// Retrieve search inputs
$start_city = $_GET['start_city'] ?? '';
$destination = $_GET['destination'] ?? '';
$travel_date = $_GET['travel_date'] ?? '';
$rooms_guests = $_GET['rooms_guests'] ?? '1';
$airline = $_GET['airline'] ?? '';
$package_type = $_GET['package_type'] ?? '';
$max_duration = $_GET['max_duration'] ?? 9;
$budget = $_GET['budget'] ?? 215000;

// Set price column based on rooms/guests input
$price_column = ($rooms_guests === '2') ? 'price_per_two' : 'price_per_person';

// Handle empty airline and package type
$airline = empty($airline) ? '%' : '%' . $airline . '%';
$package_type = empty($package_type) ? '%' : '%' . $package_type . '%';

// Get the logged-in user's email from the session
$userEmail = $_SESSION['user_email'] ?? '';

// SQL query to retrieve packages and check if each package is favorited by the user
$sql = "
SELECT td.uniq_id, td.image1, td.start_city, td.destination, td.duration, td.airline, td.package_type, td.itinerary, $price_column, td.sightseeing_details,
       IF(f.email IS NOT NULL, 1, 0) AS is_favorite,  -- Check if the package is favorited by the user
       (
           (td.start_city LIKE ?) * 6 +  -- Higher weight for starting city
           (td.destination LIKE ?) * 6 + -- Higher weight for destination
           (td.travel_date = ?) * 2      -- Moderate weight for travel date
       ) AS match_score
FROM travel_data td
LEFT JOIN favorites f ON td.uniq_id = f.uniq_id AND f.email = ?  -- Join with favorites to get liked status
WHERE (
        (td.start_city LIKE ? OR td.destination LIKE ? OR td.travel_date = ?)
    )
AND td.duration BETWEEN 1 AND ?  -- Filter by max duration
AND $price_column IS NOT NULL
AND $price_column <= ?  -- Filter by budget
AND td.airline LIKE ?
AND td.package_type LIKE ?
HAVING match_score > 0
ORDER BY match_score DESC, $price_column ASC";

// Params and types for binding
$params = [
    '%' . $start_city . '%',   // start_city
    '%' . $destination . '%',  // destination
    $travel_date,              // travel_date
    $userEmail,                // user email to check if the package is liked
    '%' . $start_city . '%',   // start_city for WHERE clause
    '%' . $destination . '%',  // destination for WHERE clause
    $travel_date,              // travel_date for WHERE clause
    $max_duration,             // max_duration
    $budget,                   // budget
    '%' . $airline . '%',      // airline
    '%' . $package_type . '%'  // package_type
];

// Updated types string to match 11 parameters
$types = 'sssssssidss'; // s=string, i=integer, d=double


// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Bind parameters if any
$stmt->bind_param($types, ...$params);

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
    <title>Tour Packages</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../CSS/listed_packages.css">
    <script src="../Javascript/listed_packages.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="search-bar">
            <!-- Update your form method in the header section -->
<form method="GET" action="">
    <input type="text" name="start_city" value="<?php echo htmlspecialchars($start_city); ?>" placeholder="Starting From">
    <input type="text" name="destination" value="<?php echo htmlspecialchars($destination); ?>" placeholder="Going To">
    <input type="date" name="travel_date" value="<?php echo htmlspecialchars($travel_date); ?>" placeholder="Starting Date">
    <input type="text" name="rooms_guests" value="<?php echo htmlspecialchars($rooms_guests); ?>" placeholder="Rooms & Guests">
    
    <!-- Search Button -->
    <button type="submit" name="search" class="search-btn">Search</button>
</form>

        </div>
    </header>

    <div class="container">
    <aside>
    <form method="GET" action="">
        <div class="filters">
            <h3>Filters</h3>

            <!-- Hidden inputs to retain search data -->
            <input type="hidden" name="start_city" value="<?php echo htmlspecialchars($start_city); ?>">
            <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
            <input type="hidden" name="travel_date" value="<?php echo htmlspecialchars($travel_date); ?>">
            <input type="hidden" name="rooms_guests" value="<?php echo htmlspecialchars($rooms_guests); ?>">

            <!-- Duration Filter -->
            <div class="filter-item">
                <h4>Total Duration (in Nights)</h4>
                <input type="range" min="1" max="9" value="<?php echo $max_duration; ?>" name="max_duration" class="slider" id="totalDurationRange" oninput="updateDurationValue(this.value)">
                <p>Max: <span id="max-duration-value"><?php echo $max_duration; ?></span></p>
            </div>

            <!-- Budget Filter -->
            <div class="filter-item">
                <h4>Budget (per person)</h4>
                <input type="range" min="3000" max="70000" step="500" value="<?php echo $budget; ?>" name="budget" class="slider" id="budgetRange" oninput="updateBudgetValue(this.value)">
                <p>₹ <span id="budget-value"><?php echo number_format($budget); ?></span></p>
            </div>

            <!-- Airline Filter -->
            <div class="filter-item">
                <h4>Airlines</h4>
                <select name="airline">
                    <option value="">Select Airline</option>
                    <option value="Indigo" <?php if (trim($airline, '%') == 'Indigo') echo 'selected'; ?>>Indigo</option>
                    <option value="SpiceJet" <?php if (trim($airline, '%') == 'SpiceJet') echo 'selected'; ?>>SpiceJet</option>
                    <option value="Vistara" <?php if (trim($airline, '%') == 'Vistara') echo 'selected'; ?>>Vistara</option>
                    <option value="Air India" <?php if (trim($airline, '%') == 'Air India') echo 'selected'; ?>>Air India</option>
                </select>
            </div>

            <!-- Package Type Filter -->
            <div class="filter-item">
                <h4>Package Type</h4>
                <select name="package_type">
                    <option value="">Select Package Type</option>
                    <option value="Standard" <?php if (trim($package_type, '%') == 'Standard') echo 'selected'; ?>>Standard</option>
                    <option value="Deluxe" <?php if (trim($package_type, '%') == 'Deluxe') echo 'selected'; ?>>Deluxe</option>
                    <option value="Premium" <?php if (trim($package_type, '%') == 'Premium') echo 'selected'; ?>>Premium</option>
                    <option value="Luxury" <?php if (trim($package_type, '%') == 'Luxury') echo 'selected'; ?>>Luxury</option>
                </select>
            </div>

            <!-- Apply Filter Button -->
            <button type="submit" name="apply_filter" class="search-btn">Apply Filter</button>
        </div>
    </form>
</aside>

<main class="package-item">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Generate the URL to the dest_details.php page with uniq_id
            $details_url = "dest_details.php?uniq_id=" . urlencode($row['uniq_id']);

            // Determine heart icon class based on favorite status
            $heartClass = $row['is_favorite'] ? 'fas fa-heart active' : 'far fa-heart';
            ?>
            <div class="package-card">
                <!-- Wrap only the image in the link -->
                <a href="<?php echo $details_url; ?>" class="package-card-link">
                    <img src="<?php echo htmlspecialchars($row['image1']); ?>" alt="Package Image" class="package-card-img">
                </a>
                <!-- Render heart icon with red color if already liked -->
                <div class="heart-icon <?php echo $row['is_favorite'] ? 'active' : ''; ?>" onclick="toggleHeart(this)">
                    <i class="<?php echo $heartClass; ?>"></i>
                </div>
                <!-- Wrap only the text in the link -->
                <a href="<?php echo $details_url; ?>" class="package-card-link">
                    <h3><?php echo htmlspecialchars($row['start_city']) . " to " . htmlspecialchars($row['destination']); ?></h3>
                    <p><?php echo htmlspecialchars($row['duration']); ?> Nights</p>
                    <p><?php echo htmlspecialchars($row['airline']); ?></p>
                    <p><?php echo htmlspecialchars($row['package_type']); ?></p>
                    <p class="sight"><?php echo htmlspecialchars($row['sightseeing_details']); ?></p>
                    <p class="price-per-person">
                        <?php
                        $pricePerPerson = number_format($row[$price_column]);
                        echo "₹" . $pricePerPerson . " / Person";
                        ?>
                    </p>
                    <p class="total-price">
                        <?php
                        if ($price_column === 'price_per_two') {
                            $totalPrice = 2 * $row['price_per_two'];
                        } else {
                            $totalPrice = 2 * $row['price_per_person'];
                        }
                        echo "Total Price: ₹" . number_format($totalPrice);
                        ?>
                    </p>
                </a>
            </div>
            <?php
        }
    } else {
        echo "<p>No packages found matching your criteria.</p>";
    }
    ?>
</main>


</div>

  <?php include "footer.php"; ?> 
</body>
</html> 














