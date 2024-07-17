
<?php
session_start();
include 'inc/header.php';
?>

<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        text-align: center;
    }

    h2 {
        margin-top: 50px;
    }

    p {
        margin-bottom: 20px;
    }

    form {
        margin-top: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    label, textarea {
        margin-bottom: 10px;
    }

    select, textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        box-sizing: border-box;
    }

    input[type="submit"] {
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
</style>

<?php
if (isset($_SESSION['order_processed']) && $_SESSION['order_processed']) {
    $_SESSION['order_processed'] = false;

    echo "<div class='container'>";
    echo "<h2>Thank You for Shopping with Us!</h2>";
    echo "<p>We appreciate your business. Your order has been successfully processed.</p>";

    echo "<h3>Rate the Product:</h3>";
    echo "<form action='rate_product.php' method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $_SESSION['last_ordered_product_id'] . "'>";
    echo "<label for='rating'>Rating:</label>";
    echo "<select name='rating' id='rating'>";
    echo "<option value='1'>1 - Poor</option>";
    echo "<option value='2'>2 - Fair</option>";
    echo "<option value='3'>3 - Good</option>";
    echo "<option value='4'>4 - Very Good</option>";
    echo "<option value='5'>5 - Excellent</option>";
    echo "</select>";
    echo "<br>";
    echo "<label for='comment'>Comment:</label>";
    echo "<textarea name='comment' id='comment' rows='4' cols='50'></textarea>";
    echo "<br>";
    echo "<input type='submit' value='Submit Rating'>";
    echo "</form>";

    echo "</div>";

} else {
    echo "<div class='container'>";
    echo "<h2>Access Denied</h2>";
    echo "<p>You are not authorized to access this page directly. Please place an order first.</p>";
    echo "</div>";
}

include 'inc/footer.php';
?>
