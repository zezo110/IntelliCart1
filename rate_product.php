<?php
session_start();
include 'inc/header.php';

// التحقق من وجود بيانات المنتج في الطلب الأخير
if (isset($_SESSION['last_ordered_product_id']) && isset($_SESSION['email']) && isset($_POST['rating'])) {
    $productId = $_SESSION['last_ordered_product_id'];
    $userEmail = $_SESSION['email'];
    $rating = $_POST['rating'];
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';

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

    // Insert the product rating into the database
    $insertRatingQuery = "INSERT INTO product_ratings (product_id, user_email, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($insertRatingQuery);
    $stmt->bind_param("isss", $productId, $userEmail, $rating, $comment);
    $stmt->execute();

    // Close the database connection
    $stmt->close();
    $connection->close();
    
    echo "<div class='container'>";
    echo "<h2 style='text-align: center; margin-top: 50px;'>Rating Submitted</h2>";
    echo "<p style='text-align: center;'>Thank you for providing your feedback. Your rating has been submitted successfully.</p>";
    echo "</div>";

} else {
    // إذا تم الوصول إلى الصفحة بدون بيانات صحيحة
    echo "<div class='container'>";
    echo "<h2 style='text-align: center; margin-top: 50px;'>Invalid Access</h2>";
    echo "<p style='text-align: center;'>Invalid access to this page. Please rate a product from the thank you page.</p>";
    echo "</div>";
}

include 'inc/footer.php';
?>
