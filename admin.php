<?php
session_start();
$_SESSION['username'] = $username;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['type']) && $_GET['type'] == 'login') {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Login process
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT * FROM admins WHERE username = :username AND password = :password");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $_SESSION['username'] = $username;
                header('Location: dashboard/pages/index.php');
                exit();
            } else {
                echo "بيانات تسجيل الدخول غير صحيحة";
            }
        }
    } elseif (isset($_GET['type']) && $_GET['type'] == 'signup') {
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
            // Signup process
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password === $confirm_password) {
                // Insert new user into database
                $stmt = $conn->prepare("INSERT INTO user_info (username, password) VALUES (:username, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->execute();

                // Redirect to home page after successful signup
                $_SESSION['username'] = $username;
                header('Location:dashboard/pages/index.php');
                exit();
            } else {
                echo "كلمتا السر لا تتطابقان";
            }
        }
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
