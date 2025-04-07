<?php
session_start();
include 'config/connection.php'; // Include database connection

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["status" => "error", "message" => "Please log in first"]);
    exit();
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_POST['product_id'];

// Check if the product already exists in the wishlist
$checkQuery = "SELECT * FROM tbl_wishlist_masters WHERE wishlist_product_id = ? AND customer_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("ii", $product_id, $customer_id);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Already in Wishlist"]);
    exit();
}

// Insert into wishlist if not exists
$query = "INSERT INTO tbl_wishlist_masters (wishlist_product_id, customer_id, wishlist_status, created_at) 
          VALUES (?, ?, 1, NOW())";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $product_id, $customer_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Added to Wishlist"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add"]);
}
?>
