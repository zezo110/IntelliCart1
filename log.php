<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['type']) && $_GET['type'] == 'login') {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT * FROM user_info WHERE email = :email AND password = :password");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['email'] = $email;
                $_SESSION['user_type'] = $row['user_type'];
                header('Location: index.php');
                exit();
            } else {
                header('Location: log.html?error=invalid');
                exit();
            }
        }
    } elseif (isset($_GET['type']) && $_GET['type'] == 'signup') {
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Check if email already exists
            $stmt = $conn->prepare("SELECT email FROM user_info WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Email already exists
                header('Location: log.html?error=email_exists');
                exit();
            } elseif ($password !== $confirm_password) {
                // Passwords do not match
                header('Location: log.html?error=password_mismatch');
                exit();
            } else {
                $stmt = $conn->prepare("INSERT INTO user_info (email, password) VALUES (:email, :password)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->execute();

                $_SESSION['email'] = $email;
                header('Location: index.php');
                exit();
            }
        }
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
