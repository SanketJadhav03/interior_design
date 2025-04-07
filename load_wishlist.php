<?php
session_start();
include 'config/connection.php';

if (!isset($_SESSION['customer_id'])) {
    echo "<li class='list-group-item text-danger text-center'>⚠️ Please log in to view your wishlist.</li>";
    exit();
}

$customer_id = $_SESSION['customer_id'];

$query = "SELECT w.wishlist_id, p.product_name, p.product_price, p.product_image, p.product_dis_value 
          FROM tbl_wishlist_masters w
          JOIN tbl_product p ON w.wishlist_product_id = p.product_id
          WHERE w.customer_id = ? AND w.wishlist_status = 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $final_price = $row['product_price'] - $row['product_dis_value'];

        echo "<li  style='display: flex; justify-content: space-between;font-size: 16px' class='list-group-item wishlist-item d-flex justify-content-between align-items-center p-3 border rounded shadow-sm'>
                <div class='wishlist-details flex-grow-1'>
                    <h6 class='mb-1' style='font-size: 16px;font-weight: bold;'>{$row['product_name']}</h6>
                    <small class='text-muted'><s>₹{$row['product_price']}</s> <strong class='text-success'>₹{$final_price}</strong></small>
                </div>
                <button class='btn btn-sm btn-danger remove-wishlist' data-id='{$row['wishlist_id']}'>
                    <i class='fa fa-trash'></i>
                </button>
              </li>";
    }
} else {
    echo "<li class='list-group-item text-warning text-center'>🛍️ Your wishlist is empty.</li>";
}

$stmt->close();
$conn->close();
?>
