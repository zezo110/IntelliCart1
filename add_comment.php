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
    $productId = $_POST['product_id'];
    $comment = $_POST['comment'];
    $email = $_SESSION['email'];

    $insertCommentQuery = "INSERT INTO product_comments (product_id, comment, user_name) VALUES (?, ?, ?)";
    $stmtComment = $connection->prepare($insertCommentQuery);
    $stmtComment->bind_param("iss", $productId, $comment, $email);
    $stmtComment->execute();

    header("Location: thank_you.php");
    exit();
}
?>
