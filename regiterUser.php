<?php
include "./config/connection.php";

$customer_name = $_POST["customer_name"];
$customer_email = $_POST["customer_email"];
$customer_phone = $_POST["customer_phone"];
$customer_password = $_POST["customer_password"]; 

$hashed_password = password_hash($customer_password, PASSWORD_BCRYPT);

$query = "INSERT INTO `tbl_customer`(`customer_name`, `customer_email`, `customer_password`, `customer_phone`) 
          VALUES ('$customer_name','$customer_email','$hashed_password','$customer_phone')";

$result = mysqli_query($conn, $query);

if ($result) {
    echo "<script>
    alert('User registration Successfully!');
    window.location.href = 'login.php';
    </script>";
} else {
    echo "<script>
    alert('User registration failed!');
    window.location.href = 'register.php';
    </script>";
}
?>
