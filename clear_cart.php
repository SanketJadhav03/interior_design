<?php
session_start();
include 'config/connection.php'; // Include database connection

$customer_id = $_SESSION['customer_id'];

$query = "DELETE FROM tbl_cart_masters WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Cart cleared successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to clear cart."]);
}
?>
