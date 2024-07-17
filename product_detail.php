<?php
session_start(); // بدء جلسة PHP

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

// Create a connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($connection->connect_error) {
    die('Connection failed: ' . $connection->connect_error);
}

// الحصول على معرف المنتج من الرابط
$productId = isset($_GET['id']) ? $_GET['id'] : die('Product ID not specified.');

// الاستعلام للحصول على تفاصيل المنتج
$query = "SELECT id, image, PD_name, description, price FROM products WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// تحقق إذا كان الطلب من نوع POST ومن زر 'Add to Cart'
if (isset($_POST['add_to_cart'])) {
    $quantity = 1; // كمية ثابتة للتبسيط، يمكن تعديلها

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }

    // إعادة تحميل الصفحة
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $productId);
    exit;
}

// تحويل البيانات الثنائية للصورة إلى Base64
if ($product) {
    $base64Image = 'data:image/jpeg;base64,' . base64_encode($product['image']);
}

// Close the database connection
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product ? $product['PD_name'] : 'Product Not Found'; ?></title>
    <link rel="stylesheet" href="ecommerce.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-eW/4WUd5S22Q6tyJTyvPWt2kZDg2jM5X8b5OjcdLzB7B1jIsuHb/WL1ABaO3N0rQa5sJ/7PL5lFPi5MvC+BnA2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .product-detail {
            display: flex;
            justify-content: space-between;
            margin: 20px;
        }

        .product-info {
            flex: 1;
            margin-left: 20px;
        }

        .product-reviews {
            margin-top: 20px;
        }

        .product-reviews ul {
            list-style-type: none;
            padding: 0;
        }

        .product-reviews li {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php include 'inc/header.php'; ?>

    <?php
    if ($product) {
        // الاستعلام للحصول على المراجعات
        $reviewsQuery = "SELECT user_email, rating, comment, rating_date FROM product_ratings WHERE product_id = ?";
        $stmtReviews = $connection->prepare($reviewsQuery);
        $stmtReviews->bind_param("i", $productId);
        $stmtReviews->execute();
        $resultReviews = $stmtReviews->get_result();
        ?>

<div class="product-detail" style="display: flex; justify-content: space-between;">
    <div class="product-info" style="width: 70%;">
        <div class="product-image">
            <img src="<?= $base64Image ?>" alt="<?= $product['PD_name'] ?>" style="max-width:500px; display: block; margin: auto;">
        </div>
        <div class="product-info">
            <h1 style="text-align:center;"><?= $product['PD_name'] ?></h1>
            <p class="description"><?= $product['description'] ?></p>
            <p class="price" style="text-align:center; font-weight:bold;">Price: $<?= $product['price'] ?></p>
            <form action="" method="post" style="text-align:center;">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" name="add_to_cart" class="button">Add to Cart</button>

            </form>
        </div>
    </div>

    <?php
    if ($resultReviews->num_rows > 0) {
        echo "<div class='product-reviews' style='width: 30%;'>";
        echo "<h2>Product Reviews</h2>";
        echo "<ul>";

        while ($row = $resultReviews->fetch_assoc()) {
            echo "<li>";
            echo "<p><strong>User: </strong>" . getEmailUsername($row['user_email']) . "</p>";
            echo "<p><strong>Rating: </strong>" . getStarRatingImages($row['rating']) . "</p>";
            echo "<p><strong>Comment: </strong>" . $row['comment'] . "</p>";
            echo "<p><strong>Date: </strong>" . $row['rating_date'] . "</p>";
            echo "</li>";
        }

        echo "</ul>";
        echo "</div>";
    } else {
        echo "<p>No reviews for this product yet.</p>";
    }
    ?>
</div>


        <?php
        $stmtReviews->close();
    } else {
        echo "<p style='text-align:center;'>Product not found.</p>";
    }
    function getEmailUsername($email) {
        // تقسيم البريد الإلكتروني باستخدام علامة "@"
        $emailParts = explode('@', $email);
        
        // إرجاع الجزء الأول (اسم المستخدم)
        return $emailParts[0];
    }

    function getStarRatingImages($rating) {
        $stars = '<span style="display: flex; gap: 1px;">'; // gap هو التباعد بين النجوم
        for ($i = 1; $i <= 5; $i++) {
            $imageSource = ($i <= $rating) ? 'star-filled.png' : 'star-empty.png'; // استخدم صور PNG للنجوم
            $stars .= '<img src="' . $imageSource . '" alt="Star" style="width: 10px;">';
        }
        $stars .= '</span>'; // نهاية علامة span
        return $stars;
    }
    
    
    
    ?>

    <?php include 'inc/footer.php'; ?>
</body>
</html>
