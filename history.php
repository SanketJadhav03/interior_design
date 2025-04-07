<?php
include "config/connection.php"; // Include database connection
include("header.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$query = "SELECT * FROM tbl_orders WHERE customer_id = '$customer_id' ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
?>
<div class="product-page-main">

    <div class="container py-5">
        <div class="row clearfix">
            <div class="prod-page-title text-center " style="padding: 40px;">
                <h2 class="text-center text-primary mb-4">Your Order History</h2>

            </div>
        </div>
        <div class="row clearfix">
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <table class="table table-bordered shadow">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Price</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>View Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= $row['order_id']; ?></td>
                                <td><?= date("d-m-Y H:i", strtotime($row['order_date'])); ?></td>
                                <td>₹<?= number_format($row['total_price'], 2); ?></td>
                                <td><?= ucfirst($row['payment_method']) == 1? "Cash On Delivery": "Txn ID: ".$row['transaction_id'] ?></td>
                                <td>
                                    <span class="badge">
                                        <?= $row['payment_status'] == 1 ? "Unpaid":"Paid"; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= ($row['order_status'] == 'Completed') ? 'success' : (($row['order_status'] == 'Processing') ? 'warning' : 'danger'); ?>">
                                        <?= $row['order_status'] == "1" ? "Pending" :( $row['order_status'] == "2" ?"Out For Delivery":($row['order_status'] == "3"? "Delivered" :"") )?>
                                    </span>
                                </td>
                                <td>
                                    <a href="order_details.php?order_id=<?= $row['order_id']; ?>" class="btn btn-info btn-sm">View</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="text-center text-muted">No orders found.</p>
            <?php } ?>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>