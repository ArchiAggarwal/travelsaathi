<?php
include "db_connect.php";

if (isset($_GET['event_id'])) { 
    $event_id = intval($_GET['event_id']); 

    // SQL query to fetch the specific event by ID
    $sql = "SELECT event_id, event_name, event_date, event_location, event_description, event_image FROM events WHERE event_id = $event_id";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/events_web.css">
</head>
<body>
    
<?php include "header.php"; ?> 
<section class="events-section">
    
    <div class="events-container">
    
        <?php
        if (isset($result) && $result->num_rows > 0) {
            // Output the specific event details
            $row = $result->fetch_assoc();

            echo '<div class="event-card">'; 
            
            echo '<h2 class="events-title">Event Details</h2>';
            echo '<img class="event-image" src="' . htmlspecialchars($row["event_image"]) . '" alt="' . htmlspecialchars($row["event_name"]) . '">';
            echo '<div class="event-content">';
            echo '<h3 class="event-title">' . htmlspecialchars($row["event_name"]) . '</h3>';
            echo '<p class="event-date">Date: ' . date("F j, Y", strtotime($row["event_date"])) . '</p>';
            echo '<p class="event-location">Location: ' . htmlspecialchars($row["event_location"]) . '</p>';
            echo '<p class="event-description">' . htmlspecialchars($row["event_description"]) . '</p>';
            echo '</div>';
            echo '</div>';
        } else {
            echo "<p>Event not found.</p>";
        }
        ?>
    </div>
</section>

<?php
// Close the database connection
$conn->close();
?>

<?php include "footer.php"; ?> 
</body>
</html>
