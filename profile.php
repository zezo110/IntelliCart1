<?php
include 'inc/header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

// إعداد الاتصال بقاعدة البيانات
$connection = new mysqli($servername, $username, $password, $dbname);


if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}



// اسم المستخدم
$userEmail = $_SESSION['email'];

// استعلام للحصول على اسم المستخدم
$userQuery = "SELECT * FROM user_info WHERE email = '$userEmail'";
$userResult = mysqli_query($connection, $userQuery);

if ($userResult) {
    $userData = mysqli_fetch_assoc($userResult);
    $userName = $userData['email']; 
} else {
    $userName = 'Unknown User';
}

// استعلام للحصول على آخر طلبين
$ordersQuery = "SELECT orders.order_id, PD_name, total_price, order_date
FROM orders
JOIN order_items ON orders.order_id = order_items.order_id
JOIN products ON order_items.product_id = products.id
JOIN user_info ON orders.user_email = user_info.email
WHERE user_info.email = '$userName'
ORDER BY orders.order_date DESC
LIMIT 2;

"; // LIMIT 2 للحصول على آخر طلبين

$ordersResult = mysqli_query($connection, $ordersQuery);

?>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">

  <div style="display: flex; justify-content: space-around; padding: 20px;">
    <div style="text-align: center; padding-top: 50px;">
      <img src="img/profile-picture.webp" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%;">
      <h2 style="margin-top: 10px;"><?php echo $userName; ?></h2>
    </div>

    <div style="max-width: 600px; padding-top: 30px;">
      <h3>Latest Orders</h3>
      <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
          <tr>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Product</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Price</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Order </th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Date </th>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($orderData = mysqli_fetch_assoc($ordersResult)) {
              echo "<tr>";
              echo "<td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>{$orderData['PD_name']}</td>";
              echo "<td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>{$orderData['total_price']}</td>";
              echo "<td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>{$orderData['order_id']}</td>";
              echo "<td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>{$orderData['order_date']}</td>";
              echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

<!--===FOOTER===-->
<?php include 'inc/footer.php'; ?>
<!--===FOOTER===-->
</body>
</html>
