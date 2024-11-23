<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../CSS/checkout.css">
</head>
<body>
<section>
        <?php include "header.php"; ?>
</section>
    <div class="checkout-container">
        <div class="billing-details">
            <h2>Billing Details</h2>
            <form>
                <label for="first-name">First Name <span>*</span></label>
                <input type="text" id="first-name" name="first-name" required>

                <label for="last-name">Last Name <span>*</span></label>
                <input type="text" id="last-name" name="last-name" required>

                <label for="country">Country/Region <span>*</span></label>
                <select id="country" name="country">
                    <option value="india">India</option>
                    <!-- Other countries -->
                </select>

                <label for="street-address">Street Address <span>*</span></label>
                <input type="text" id="street-address" name="street-address" placeholder="House number and street name" required>

                <label for="town-city">Town/City <span>*</span></label>
                <input type="text" id="town-city" name="town-city" required>

                <label for="state">State <span>*</span></label>
                <input type="text" id="state" name="state" required>

                <label for="pin-code">PIN Code <span>*</span></label>
                <input type="text" id="pin-code" name="pin-code" required>
            </form>
        </div>

        <div class="order-summary">
            <h2>Your Packages</h2>
            <div class="order-item">
                <!-- This span will display the fetched package name -->
                <span id="package-name">
                    <?php
                        // Fetch uniq_id from URL
                        $uniq_id = $_GET['uniq_id'] ?? '';

                        // Connect to the database
                        include "db_connect.php";

                        // Fetch package data from travel_data table
                        $stmt = $conn->prepare("SELECT package_name, price_per_person, price_per_two FROM travel_data WHERE uniq_id = ?");
                        $stmt->bind_param("s", $uniq_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            echo htmlspecialchars($row['package_name']);
                            $price_per_person = $row['price_per_person'];
                            $price_per_two = $row['price_per_two'];
                        } else {
                            echo "Package not found";
                            $price_per_person = 0;
                            $price_per_two = 0;
                        }

                        $stmt->close();
                        $conn->close();
                    ?>
                </span>
                <!-- This span will display the price based on the package type -->
                <span id="package-price">₹0.00</span>
            </div>

            <label for="package-type">Select Package Type:</label>
            <select id="package-type" onchange="updatePrice()">
                <option value="per_person">Price per Person</option>
                <option value="per_two">Price for Two</option>
            </select>
<br><br><br><br>
            <div class="order-total">
                <span>Subtotal:</span>
                <span id="subtotal">₹0.00</span>
            </div>
            <button class="place-order">Place Order</button>

            <div class="payment-warning">
                <p>Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.</p>
            </div>
        </div>
    </div>

   

    <script>
        // Fetch PHP variables for use in JavaScript
        const pricePerPerson = <?php echo json_encode($price_per_person); ?>;
        const pricePerTwo = <?php echo json_encode($price_per_two); ?>;

        function updatePrice() {
            const packageType = document.getElementById("package-type").value;
            let price = 0;

            if (packageType === "per_person") {
                price = pricePerPerson;
            } else if (packageType === "per_two") {
                price = pricePerTwo;
            }

            document.getElementById("package-price").innerText = `₹${price}`;
            document.getElementById("subtotal").innerText = `₹${price}`;
        }

        // Initialize price display
        updatePrice();
    </script>
</body>
</html>



