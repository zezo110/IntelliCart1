<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve shipping details
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Retrieve payment method
    $paymentMethod = $_POST['payment_method'];

    // Retrieve products from the cart
    $cart = $_SESSION['cart'];

    // Calculate total price
    $totalPrice = 0;
    foreach ($cart as $productId => $quantity) {
        $productDetails = getProductDetails($productId, $connection);
        $productTotal = $quantity * $productDetails['price'];
        $totalPrice += $productTotal;
    }

    // Insert order details into the database
$insertOrderQuery = "INSERT INTO orders (name, phone, address, payment_method, total_price, user_email) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $connection->prepare($insertOrderQuery);
$stmt->bind_param("ssssds", $name, $phone, $address, $paymentMethod, $totalPrice, $_SESSION['email']);
$stmt->execute();


    // Retrieve the last inserted order ID
    $orderId = $stmt->insert_id;

    // Insert products into the order_items table
    $insertOrderItemsQuery = "INSERT INTO order_items (order_id, product_id, quantity, price_per_item) VALUES (?, ?, ?, ?)";
    $stmtOrderItems = $connection->prepare($insertOrderItemsQuery);

    foreach ($cart as $productId => $quantity) {
        $productDetails = getProductDetails($productId, $connection);
        $pricePerItem = $productDetails['price'];

        $stmtOrderItems->bind_param("iiid", $orderId, $productId, $quantity, $pricePerItem);
        $stmtOrderItems->execute();
    }

    // Clear the cart after the order is processed
    $_SESSION['cart'] = array();

    // Set session variables for thank_you.php
    $_SESSION['order_processed'] = true;
    $_SESSION['last_ordered_product_id'] = $productId; // تخزين معرّف المنتج الأخير

    // Redirect to thank_you.php
    header("Location: thank_you.php");
    exit();
}

function getProductDetails($productId, $connection) {
    $query = "SELECT PD_name, price FROM products WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return array('name' => $row['PD_name'], 'price' => $row['price']);
    } else {
        return array('name' => 'Unknown Product', 'price' => 0);
    }
}
?>
