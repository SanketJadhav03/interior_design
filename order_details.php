<?php
include "config/connection.php";
include("header.php");

if (!isset($_SESSION['customer_id']) || !isset($_GET['order_id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = $_GET['order_id'];
$query = "SELECT * FROM tbl_orders WHERE order_id = '$order_id' AND customer_id = '{$_SESSION['customer_id']}'";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    echo "<p class='text-center text-danger'>Order not found!</p>";
    exit();
}
?>
<style>
    .rating-stars {
        font-size: 18px;
        color: gold;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .review-text {
        font-size: 14px;
        color: #555;
        margin-top: 5px;
        font-style: italic;
    }

    .rating-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: start;
    }

    .star-rating label {
        font-size: 24px;
        color: #ccc;
        /* Default gray */
        cursor: pointer;
        transition: color 0.2s;
    }

    /* Highlight only when hovered or selected */
    .star-rating input:checked~label,
    .star-rating label:hover,
    .star-rating label:hover~label {
        color: gold;
    }

    textarea {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ddd;
        resize: none;
    }

    button {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background: #218838;
    }
</style>
<div class="product-page-main">

    <div class="container py-5">
        <div class="row clearfix">
            <div class="prod-page-title text-center " style="padding: 40px;">
                <h2 class="text-center text-primary mb-4">Order Details (Order #<?= $order['order_id']; ?>)</h2>

            </div>
        </div>
        <div style="max-width: 1000px; background: white; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px; margin: 20px auto; border-left: 5px solid #007bff;">
            <h2 style="text-align: center; color: #007bff; margin-bottom: 15px;">Order Summary</h2>
            <div style="display: flex;justify-content: space-between;">

                <p style="font-size: 16px; color: #333; margin-bottom: 8px;">
                    <strong style="color: #555;">📅 Order Date:</strong> <?= date("d-m-Y H:i", strtotime($order['order_date'])); ?>
                </p>


                <p style="font-size: 16px; color: #333; margin-bottom: 8px;">
                    <strong style="color: #555;">🚚 Order Status:</strong>
                    <span style="padding: 6px 12px; border-radius: 5px; color: white; font-weight: bold; background-color: 
            <?= ($order['order_status'] == "1") ? '#ffc107' : (($order['order_status'] == "2") ? '#17a2b8' : (($order['order_status'] == "3") ? '#28a745' : '#dc3545')); ?>;">
                        <?= $order['order_status'] == "1" ? "Pending" : ($order['order_status'] == "2" ? "Out For Delivery" : ($order['order_status'] == "3" ? "Delivered" : "")); ?>
                    </span>
                </p>
            </div>
            <hr>
            <div style=" display: flex;justify-content: space-between;">
                <p style="font-size: 16px; color: #333; margin-bottom: 8px;">
                    <strong style="color: #555;">💰 Total Price:</strong> <span style="color: #28a745; font-weight: bold;">₹<?= number_format($order['total_price'], 2); ?></span>
                </p>
                <p style="font-size: 16px; color: #333; margin-bottom: 8px;">
                    <strong style="color: #555;">💳 Payment Method:</strong> <?= ucfirst($order['payment_method']) == "1" ? "Cash on Delivery" : "Online"; ?>
                </p>



                <p style="font-size: 16px; color: #333; margin-bottom: 8px;">
                    <strong style="color: #555;">💵 Payment Status:</strong>
                    <span style="padding: 6px 12px; border-radius: 5px; color: white; font-weight: bold; background-color: <?= $order['payment_status'] == 1 ? '#dc3545' : '#28a745'; ?>;">
                        <?= $order['payment_status'] == 1 ? "Unpaid" : "Paid"; ?>
                    </span>
                </p>
            </div>
            <hr>
            <p style=" font-size: 16px; color: #333; margin-bottom: 8px;">
                <strong style="color: #555;">📍 Shipping Address:</strong> <?= ucfirst($order['shipping_address']); ?>
            </p>

        </div>

        <div class="row clearfix">
            <div class="row clearfix">
                <div class="prod-page-title text-center " style="padding: 40px;">
                    <h2 class="mt-4">Ordered Products</h2>
                </div>
            </div>
            <table class="table table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Review</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $customer_id = $_SESSION['customer_id'];
                    $productQuery = "SELECT p.product_id, p.product_name, oi.quantity, oi.price, 
                        r.rating, r.review_rating
                     FROM tbl_order_items oi 
                     JOIN tbl_product p ON oi.product_id = p.product_id 
                     LEFT JOIN tbl_ratings r ON r.product_id = p.product_id 
                        AND r.order_id = '$order_id' 
                        AND r.customer_id = '$customer_id'
                     WHERE oi.order_id = '$order_id'";
                    $productResult = mysqli_query($conn, $productQuery);
                    while ($item = mysqli_fetch_assoc($productResult)) {
                    ?>
                        <tr>
                            <td><?= $item['product_name']; ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td>₹<?= number_format($item['price'], 2); ?></td>
                            <td>
                                <?php if ($item['rating'] !== null) { ?>
                                    <div class="rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $item['rating'] ? "⭐" : "☆";
                                        } ?>
                                        <span>(<?= $item['rating']; ?>/5)</span>
                                    </div>
                                    <p class="review-text"><?= htmlspecialchars($item['review_rating']); ?></p>
                                <?php } else { ?>
                                    <form action="submit_rating.php" method="POST" class="rating-form">
                                        <input type="hidden" name="order_id" value="<?= $order_id; ?>">
                                        <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                                        <input type="hidden" name="customer_id" value="<?= $customer_id; ?>">

                                        <div class="star-rating">
                                            <input type="radio" name="rating" value="1" id="star1-<?= $item['product_id']; ?>"><label for="star1-<?= $item['product_id']; ?>">⭐</label>
                                            <input type="radio" name="rating" value="2" id="star2-<?= $item['product_id']; ?>"><label for="star2-<?= $item['product_id']; ?>">⭐</label>
                                            <input type="radio" name="rating" value="3" id="star3-<?= $item['product_id']; ?>"><label for="star3-<?= $item['product_id']; ?>">⭐</label>
                                            <input type="radio" name="rating" value="4" id="star4-<?= $item['product_id']; ?>"><label for="star4-<?= $item['product_id']; ?>">⭐</label>
                                            <input type="radio" name="rating" value="5" id="star5-<?= $item['product_id']; ?>" required><label for="star5-<?= $item['product_id']; ?>">⭐</label>
                                        </div>

                                        <textarea name="review_rating" placeholder="Write a review..." required></textarea>
                                        <button type="submit">Submit</button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
        <div class="row clearfix">
            <div class="prod-page-title text-center " style="padding: 40px;">
                <a href="history.php" class="btn btn-primary mt-3"> <i class="fa fa-long-arrow-left" style="font-weight: bold;"></i> Back to Order History</a>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>