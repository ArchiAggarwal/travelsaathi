<?php
include("db_connect.php");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = htmlspecialchars($_POST['event_name']);
    $event_date = htmlspecialchars($_POST['event_date']);
    $event_location = htmlspecialchars($_POST['event_location']);
    $event_description = htmlspecialchars($_POST['event_description']);
    
    // Handle file upload
    $event_image = $_FILES['event_image']['name'];
    $target_dir = "images/"; // Directory to save uploaded images
    $target_file = $target_dir . basename($event_image);
    
    // Check if image file is a valid image
    $check = getimagesize($_FILES['event_image']['tmp_name']);
    if ($check !== false) {
        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $target_file)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, event_location, event_description, event_image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $event_name, $event_date, $event_location, $event_description, $target_file);

            if ($stmt->execute()) {
                echo "New event uploaded successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}

// Fetch events from the database
$sql = "SELECT * FROM events ORDER BY event_date ASC";
$result = mysqli_query($conn, $sql);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../CSS/admindash.css">
</head>
<body>
    <header class="admin-header">
        <h1>Admin Dashboard</h1>
        <div class="admin-options">
            <a href="change_password.php">Change Password</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <section class="upload-section">
        <h2>Upload New Event</h2>
        <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
            <label for="event_name">Event Name:</label>
            <input type="text" id="event_name" name="event_name" required>

            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" required>

            <label for="event_location">Event Location:</label>
            <input type="text" id="event_location" name="event_location" required>

            <label for="event_description">Event Description:</label>
            <textarea id="event_description" name="event_description" required></textarea>

            <label for="event_image">Event Image:</label>
            <input type="file" id="event_image" name="event_image" required>

            <button type="submit">Upload Event</button>
        </form>
    </section>

    <section class="events-section">
        <h2>Upcoming Events</h2>
        <div class="events-container">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="event-card">
                    <img class="event-image" src="<?php echo htmlspecialchars($row['event_image']); ?>" alt="<?php echo htmlspecialchars($row['event_name']); ?>">
                    <h3 class="event-name"><?php echo htmlspecialchars($row['event_name']); ?></h3>
                    <p class="event-date">Date: <?php echo htmlspecialchars($row['event_date']); ?></p>
                    <p class="event-location">Location: <?php echo htmlspecialchars($row['event_location']); ?></p>
                    <p class="event-description"><?php echo htmlspecialchars($row['event_description']); ?></p>
                    <a href="edit_events.php?event_id=<?php echo $row['event_id']; ?>" class="edit-button">Edit</a>
                </div>
            <?php } ?>
        </div>
    </section>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>



