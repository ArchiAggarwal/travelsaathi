<?php
include "db_connect.php";

// Fetch all companies from the company_login table
$sql = "SELECT company_id, company_name, description, contact_information, logo_path FROM company_login";
$result = $conn->query($sql);

$companies = [];
if ($result->num_rows > 0) {
    // Fetch all companies data
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
} else {
    echo "No company information found.";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Guide</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../CSS/travel_guide.css">
</head>
<body>
    <?php include "header.php"; ?>
    <div class="container-travel">
        <h1>Travel Guide</h1>

        <div class="company-grid">
            <?php if (!empty($companies)): ?>
                <?php foreach ($companies as $company): ?>
                    <div class="company">
                        <img src="<?php echo htmlspecialchars($company['logo_path']); ?>" alt="<?php echo htmlspecialchars($company['company_name']); ?> Logo" class="company-logo">
                        
                        <div class="company-info">
                            <h2><?php echo htmlspecialchars($company['company_name']); ?></h2>
                            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($company['description'])); ?></p>
                            <p>
                                <a href="packages.php?company_id=<?php echo urlencode($company['company_id']); ?>" class="btn btn-primary">View Packages</a>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No company information available.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>
