<?php
session_start();
include 'config/connection.php';

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["status" => "error", "message" => "Please log in first"]);
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Get shipping address from request
if (!isset($_POST['shipping_address']) || empty(trim($_POST['shipping_address']))) {
    echo json_encode(["status" => "error", "message" => "Shipping address is required"]);
    exit();
}

$shipping_address = trim($_POST['shipping_address']);
$payment_method = trim($_POST['payment_method']);  // Default to Cash on Delivery
$transaction_id = !empty(trim($_POST['transaction_id'])) ? trim($_POST['transaction_id']) : NULL;
$payment_status = 1; // 1 = Pending, 2 = Paid

// Fetch cart items
$cart_query = "SELECT c.cart_product_id, c.cart_product_qty, p.product_price, p.product_dis_value 
               FROM tbl_cart_masters c
               JOIN tbl_product p ON c.cart_product_id = p.product_id
               WHERE c.customer_id = ? AND c.cart_status = 1";

$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += ($row['product_price'] - $row['product_dis_value']) * $row['cart_product_qty'];
}
$stmt->close();

if (empty($cart_items)) {
    echo json_encode(["status" => "error", "message" => "Cart is empty"]);
    exit();
}

// Begin transaction
$conn->begin_transaction();

try {
    // Insert order into tbl_orders
    // Insert order into tbl_orders
    $order_query = "INSERT INTO tbl_orders (customer_id, order_date, order_status, total_price, shipping_address, payment_method, payment_status, created_at, updated_at, transaction_id) 
VALUES (?, NOW(), '1', ?, ?, ?, ?, NOW(), NOW(), ?)";

    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("idsssi", $customer_id, $total_price, $shipping_address, $payment_method, $payment_status, $transaction_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert order items into tbl_order_items
    $order_items_query = "INSERT INTO tbl_order_items (order_id, product_id, quantity, price, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($order_items_query);

    foreach ($cart_items as $item) {
        $final_price = $item['product_price'] - $item['product_dis_value']; // Apply discount
        $stmt->bind_param("iiid", $order_id, $item['cart_product_id'], $item['cart_product_qty'], $final_price);
        $stmt->execute();
    }
    $stmt->close();

    // Clear the cart for the customer
    $clear_cart_query = "DELETE FROM tbl_cart_masters WHERE customer_id = ?";
    $stmt = $conn->prepare($clear_cart_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $stmt->close();

    // Commit transaction
    $conn->commit();

    // Redirect to success page
    header("Location: order_success.php");
    exit();
} catch (Exception $e) {
    // Rollback transaction in case of failure
    $conn->rollback();
    echo $e->getMessage();
    echo json_encode(["status" => "error", "message" => "Order processing failed"]);
    exit();
}

$conn->close();
