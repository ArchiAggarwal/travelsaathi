<?php
include 'db_connect.php';  // Include your database connection

// Fetch the email from the URL
if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);
} else {
    header("Location: error.php"); 
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    
    // Define directory to store uploaded files
    $uploadDir = "uploads/";

    // Handle image upload
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == 0) {
        $imageFileName = basename($_FILES['image_path']['name']);
        $imagePath = $uploadDir . $imageFileName;
        move_uploaded_file($_FILES['image_path']['tmp_name'], $imagePath);
    } else {
        $imagePath = '';
    }

    // Handle video upload
    if (isset($_FILES['video_path']) && $_FILES['video_path']['error'] == 0) {
        $videoFileName = basename($_FILES['video_path']['name']);
        $videoPath = $uploadDir . $videoFileName;
        move_uploaded_file($_FILES['video_path']['tmp_name'], $videoPath);
    } else {
        $videoPath = '';
    }

    // Insert data into database
    $query = "INSERT INTO blogs_and_vlogs (email, title, description, image_path, video_path, type) 
              VALUES ('$email', '$title', '$description', '$imagePath', '$videoPath', '$type')";
    if (mysqli_query($conn, $query)) {
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Blog/Vlog - TravelSaathi</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../CSS/blogsvlogs.css">
</head>
<body>
    <?php include "header_blogs.php"; ?>
    <div class="container">
        <h2>Upload Your Blog or Vlog</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?email=<?php echo $email; ?>" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="image_path">Upload Image:</label>
            <input type="file" name="image_path" id="image_path" accept="image/*" required>

            <label for="video_path">Upload Video (Optional):</label>
            <input type="file" name="video_path" id="video_path" accept="video/*">

            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="blog">Blog</option>
                <option value="vlog">Vlog</option>
            </select>

            <button type="submit">Submit</button>
        </form>
   
        <h2>Your Blogs and Vlogs</h2>
        <ul>
            <?php
            // Query to retrieve blogs/vlogs by the user
            $query = "SELECT title, description, image_path, video_path, type FROM blogs_and_vlogs WHERE email = '$email'";
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<li><strong>Title:</strong> {$row['title']}<br>";
                    echo "<strong>Description:</strong> {$row['description']}<br>";
                    echo "<strong>Type:</strong> {$row['type']}<br>";
                    echo "<strong>Image:</strong> <img src='{$row['image_path']}' alt='Image' style='max-width:150px;'><br>";

                    if (!empty($row['video_path'])) {
                        echo "<strong>Video:</strong> <a href='{$row['video_path']}' target='_blank'>Watch Video</a><br>";
                    }
                    echo "<br></li>";
                }
            } else {
                echo "<p>No blogs or vlogs found for your account.</p>";
            }
            ?>
        </ul>
    </div>
</body>
</html>

