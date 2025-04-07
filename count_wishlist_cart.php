<?php
session_start();
include 'config/connection.php';

$response = ["wishlist_count" => 0, "cart_count" => 0];

if (isset($_SESSION['customer_id'])) {
    $customer_id = $_SESSION['customer_id'];

    // Count Wishlist Items
    $wishlist_query = "SELECT COUNT(*) AS count FROM tbl_wishlist_masters WHERE customer_id = ? AND wishlist_status = 1";
    $stmt = $conn->prepare($wishlist_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $response["wishlist_count"] = $result["count"];

    // Count Cart Items
    $cart_query = "SELECT COUNT(*) AS count FROM tbl_cart_masters WHERE customer_id = ?";
    $stmt = $conn->prepare($cart_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $response["cart_count"] = $result["count"];

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
