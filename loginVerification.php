<?php
session_start();
include "./config/connection.php";

$username = $_POST["username"];
$password = $_POST["password"];

// Fetch user details based on email or phone
$query = "SELECT * FROM tbl_customer WHERE `customer_email` = '$username' OR `customer_phone` = '$username'";
$result = mysqli_query($conn, $query);

if ($result->num_rows > 0) {
    $row = mysqli_fetch_array($result);
    $hashed_password = $row["customer_password"];
 
    if (password_verify($password, $hashed_password)) {
        $_SESSION["customer_id"] = $row["customer_id"];
        $_SESSION["customer_name"] = $row["customer_name"]; 

        echo "<script>alert('Login Success!');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Password does not match!');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
    }
} else {
    echo "<script>alert('Username does not exist!');</script>";
    echo "<script>window.location.href = 'login.php';</script>";
}
?>
