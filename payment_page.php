<?php
session_start();
include 'inc/header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
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

<style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F0F2F5;
}

.container {
    max-width: 960px;
    margin: 20px auto;
    padding: 20px;
    background-color: #FFFFFF;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.header {
    color: #FFFFFF;
    background-color: #ff793f;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.cart-item, .shipping-details, form {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    margin-bottom: 20px;
}

.table-header {
    background-color: #ff793f;
    color: #FFFFFF;
}

.table-row {
    border-bottom: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
}

.input-field {
    width: 100%;
    padding: 8px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.submit-btn {
    display: inline-block;
	background-color: var(--first-color);
	color: var(--white-color);
	padding: 1rem; 
	font-size: var(--small-font-size);
	font-weight: var(--font-semi);
	border-radius: 20px;
	transition: .3s;
	box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  	transition: all 0.3s cubic-bezier(.25,.8,.25,1);
}


.submit-btn:hover {
	background-color: var(--first-color-alt);
	box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
}

.radio-field {
    margin-right: 10px;
}

.footer {
    background-color: #FFFFFF;
    color: #666;
    text-align: center;
    padding: 20px 0;
    border-radius: 5px;
    margin-top: 20px;
}
</style>

<div class="container">
    <div class="header">
        <h2>Your Order</h2>
    </div>

    <?php
    $totalPrice = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        echo "<table style='width: 100%;'>";
        echo "<tr class='table-header'>";
        echo "<th>Product</th>";
        echo "<th>Quantity</th>";
        echo "<th>Price per item</th>";
        echo "<th>Total</th>";
        echo "</tr>";

        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $productDetails = getProductDetails($productId, $connection);
            $productTotal = $quantity * $productDetails['price'];

            echo "<tr class='table-row'>";
            echo "<td>" . htmlspecialchars($productDetails['name']) . "</td>";
            echo "<td>$quantity</td>";
            echo "<td>$" . $productDetails['price'] . "</td>";
            echo "<td>$" . $productTotal . "</td>";
            echo "</tr>";

            $totalPrice += $productTotal;
        }

        echo "</table>";
        echo "<p style='text-align: center; font-style: italic; font-size: 16px;'>Total Price: $" . $totalPrice . "</p>";
    } else {
        echo "<p style='text-align: center;'>Your cart is empty.</p>";
    }
    ?>

    <div class='shipping-details'>
        <h2>Shipping Details</h2>
        <form method='post' action='process_order.php' onsubmit="return validateForm();">
            <div style='display: flex; flex-direction: column; margin-bottom: 15px;'>
                <label>Name:</label>
                <input type='text' name='name' class='input-field' required>
            </div>
            <div style='display: flex; flex-direction: column; margin-bottom: 15px;'>
                <label>Phone:</label>
                <input type='text' name='phone' class='input-field' required>
            </div>
            <div style='display: flex; flex-direction: column; margin-bottom: 15px;'>
                <label>Address:</label>
                <input type='text' name='address' class='input-field' required>
            </div>
            
            <div style='margin-bottom: 15px;'>
                <h2>Payment Method</h2>
                <label><input type='radio' name='payment_method' value='cash_on_delivery' class='radio-field' required> Cash on Delivery</label><br>
                <label><input type='radio' name='payment_method' value='paypal' class='radio-field'> PayPal</label>
            </div>
            
            <input type='submit' value='Place Order' class='submit-btn'>
        </form>
    </div>
</div>

<script>
function validateForm() {
    var inputs = document.querySelectorAll('.input-field');
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() === '') {
            alert('All fields are required. Please fill out all fields.');
            return false;
        }
    }
    return true;
}
</script>

