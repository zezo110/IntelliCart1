<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'inc/header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function addToCart($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

if (isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    unset($_SESSION['cart'][$productId]);
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
    text-align: center;
    padding: 10px 0;
    margin-bottom: 30px; 
}

html, body {
    height: 100%;
    margin: 0;
}

.page-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.content-wrap {
    flex: 1;
}

.footer {
    text-align: center;
    padding: 20px 0;
    position: relative;
    bottom: 0;
    width: 100%;
}

.cart-item {
    margin: 15px 0;
    padding: 15px;
    background-color: #EAEAEA;
    border-radius: 10px; 
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.cart-item:not(:last-child) {
    border-bottom: 2px solid #DDD; 
}

.cart-item-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
}

.cart-item-info > div {
    margin: 5px 0;
}

.button-link {
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

.button-link:hover {
	background-color: var(--first-color-alt);
	box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
}


.total-price {
    text-align: right;
    font-size: 18px;
    font-weight: bold;
}
</style>
<div class="page-container">
    <div class="content-wrap">
    <div class="container">
            <div class="header">
                <h2>Your Cart</h2>
            </div>

            <?php
            $totalPrice = 0;
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $productId => $quantity) {
                    $productDetails = getProductDetails($productId, $connection);
                    $productTotal = $quantity * $productDetails['price'];
                    ?>

                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div>Product: <?= htmlspecialchars($productDetails['name']) ?></div>
                            <div>Quantity: <?= $quantity ?></div>
                            <div>Price per item: $<?= $productDetails['price'] ?></div>
                            <div>Total: $<?= $productTotal ?></div>
                            <div>
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?= $productId ?>">
                                    <input type="submit" name="remove_from_cart" value="Remove" class="button-link">
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php
                    $totalPrice += $productTotal;
                }

                echo "<div class='total-price'>Total Price: $" . $totalPrice . "</div>";
            } else {
                echo "<p>Your cart is empty.</p>";
            }

            if (!empty($_SESSION['cart'])) {
                if (isset($_SESSION['email'])) {
                    echo "<a href='payment_page.php' class='button-link'>Continue to Payment</a>";
                } else {
                    echo "<a href='log.html' class='button-link'>Please Login to Continue</a>";
                }
            }
            ?>
        </div>
    </div>

    <div class="footer">
        <?php include 'inc/footer.php'; ?>
    </div>
</div>

<?php $connection->close(); ?>