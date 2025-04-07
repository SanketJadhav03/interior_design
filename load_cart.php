<?php
session_start();
include 'config/connection.php'; // Include database connection

$response = ["status" => "error", "message" => "Cart is empty", "cart_items" => []];

if (!isset($_SESSION['customer_id'])) {
    $response["message"] = "Please log in to view your cart.";
    echo json_encode($response);
    exit();
}

$customer_id = $_SESSION['customer_id'];

$query = "SELECT c.cart_id, p.product_name, p.product_price,p.product_dis_value, c.cart_product_qty 
          FROM tbl_cart_masters c
          JOIN tbl_product p ON c.cart_product_id = p.product_id
          WHERE c.customer_id = ? AND c.cart_status = 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = [
            "cart_id" => $row["cart_id"],
            "product_name" => $row["product_name"],
            "product_price" => $row["product_price"],
            "cart_product_qty" => $row["cart_product_qty"],
            "total_price" => ($row["product_price"]- $row["product_dis_value"]) * $row["cart_product_qty"] // Total price calculation
        ];
    }
    $response["status"] = "success";
    $response["message"] = "Cart loaded successfully";
    $response["cart_items"] = $cart_items;
} else {
    $response["message"] = "Your cart is empty.";
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
