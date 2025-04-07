<?php
include 'config/connection.php'; // Include your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $customer_id = $_POST['customer_id'];
    $rating = $_POST['rating'];
    $review_rating = trim($_POST['review_rating']);

    $query = "INSERT INTO tbl_ratings (order_id, product_id, customer_id, rating, review_rating, created_at) 
              VALUES ('$order_id', '$product_id', '$customer_id', '$rating', '$review_rating', NOW()) 
              ON DUPLICATE KEY UPDATE rating = VALUES(rating), review_rating = VALUES(review_rating)";
    
    if (mysqli_query($conn, $query)) {
        header("Location: order_details.php?order_id=$order_id"); // Redirect back to order page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
