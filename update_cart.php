<?php
session_start();
include 'config/connection.php';

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["status" => "error", "message" => "Please log in first"]);
    exit();
}

$cart_id = $_POST['cart_id'];
$new_qty = $_POST['qty'];

$query = "UPDATE tbl_cart_masters SET cart_product_qty = ? WHERE cart_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $new_qty, $cart_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update"]);
}
?>
