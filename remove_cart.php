<?php
session_start();
include 'config/connection.php';

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["status" => "error", "message" => "Please log in first"]);
    exit();
}

if (!isset($_POST['cart_id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

$customer_id = $_SESSION['customer_id'];
$cart_id = $_POST['cart_id'];

$query = "DELETE FROM tbl_cart_masters WHERE cart_id = ? AND customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $cart_id, $customer_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Removed from cart"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to remove"]);
}

$stmt->close();
$conn->close();
?>
