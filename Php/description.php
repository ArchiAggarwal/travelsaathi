<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../CSS/description.css">
</head>

<?php
include "db_connect.php";
include "header.php";

// Get the ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the specific blog or vlog details based on the ID
$sql = "SELECT title, description, image_path FROM blogs_and_vlogs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if a result was found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo '<section-description>';
    echo '<h1>' . htmlspecialchars($row['title']) . '</h1>';
    echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['title']) . '">';
    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
    echo '</section>';
} else {
    echo "<p>Blog or vlog not found.</p>";
}

// Close the database connection
$conn->close();
?>


