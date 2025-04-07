<?php
session_start();
include 'config/connection.php'; // Include database connection

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["status" => "error", "message" => "Please log in first"]);
    exit();
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_POST['product_id'];

// Check if the product already exists in the cart
$check_query = "SELECT cart_product_qty FROM tbl_cart_masters WHERE customer_id = ? AND cart_product_id = ? AND cart_status = 1";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $customer_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    // Product exists, so update the quantity
    $update_query = "UPDATE tbl_cart_masters SET cart_product_qty = cart_product_qty + 1 WHERE customer_id = ? AND cart_product_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $customer_id, $product_id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Quantity increased"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update quantity"]);
    }
} else {
    // Product not in cart, insert new entry
    $insert_query = "INSERT INTO tbl_cart_masters (cart_product_id, cart_product_qty, customer_id, cart_status) VALUES (?, 1, ?, 1)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ii", $product_id, $customer_id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Added to cart"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add to cart"]);
    }
}

$stmt->close();
$conn->close();
?>
        