<?php
include "header.php";
include "db_connect.php";

// SQL query to fetch data from the discounts table
$sql = "SELECT id, email, company_name, discount_message FROM discounts";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discounts</title>
    <link rel="stylesheet" href="../CSS/discount_web.css">
</head>
<body>

<h2>Available Discounts</h2>

<div class="container-discount">
    <?php
    // Check if the query returned any results
    if ($result->num_rows > 0) {
        // Loop through each row in the result and create a card for each discount
        while($row = $result->fetch_assoc()) {
            echo "<div class='card'>";
            echo "<h3>" . $row["company_name"] . "</h3>";
            echo "<p>" . $row["discount_message"] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No discounts found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
</div>
<?php include "footer.php"; ?>
</body>
</html>
