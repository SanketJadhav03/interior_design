<?php
session_start();
include 'config/connection.php';

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["status" => "error", "message" => "Please log in first"]);
    exit();
}

if (!isset($_POST['wishlist_id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

$customer_id = $_SESSION['customer_id'];
$wishlist_id = $_POST['wishlist_id'];

$query = "DELETE FROM tbl_wishlist_masters WHERE wishlist_id = ? AND customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $wishlist_id, $customer_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Removed from wishlist"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to remove"]);
}

$stmt->close();
$conn->close();
?>
