<?php
include 'config/connection.php';
include 'header.php'; // Include the header

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch user details (assuming you have a `tbl_customer` table)
$user_query = "SELECT customer_name, customer_email, customer_phone FROM tbl_customer WHERE customer_id = ?";
$stmt_user = $conn->prepare($user_query);
$stmt_user->bind_param("i", $customer_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user = $user_result->fetch_assoc();

// Fetch cart items
$query = "SELECT c.cart_id, c.cart_product_id, p.product_dis_value, c.cart_product_qty, p.product_name, p.product_price
          FROM tbl_cart_masters c
          JOIN tbl_product p ON c.cart_product_id = p.product_id
          WHERE c.customer_id = ? AND c.cart_status = 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += ($row['product_price'] - $row['product_dis_value']) * $row['cart_product_qty'];
}
?>

<div class="product-page-main">
    <div class="container mt-4">
        <div class="row clearfix">
            <div class="prod-page-title text-center " style="padding: 60px;">
                <h1> <i class="fa fa-info-circle"></i> Confirm Your Order</h1>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>

            <?php foreach ($cart_items as $item) { ?>
                <tr>
                    <td><?= $item['product_name']; ?></td>
                    <td>₹<?= number_format(($item['product_price'] - $item["product_dis_value"]), 2); ?></td>
                    <td>
                        <input type="number" class="qty form-control" data-id="<?= $item['cart_id']; ?>" value="<?= $item['cart_product_qty']; ?>" min="1">
                    </td>
                    <td class="total-price">₹<?= number_format(($item['product_price'] - $item["product_dis_value"]) * $item['cart_product_qty'], 2); ?></td>
                </tr>
            <?php } ?>
        </table>

        <form id="orderForm" action="process_order.php" method="POST">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="order_name">Full Name</label>
                        <input readonly type="text" name="order_name" id="order_name" class="form-control" value="<?= htmlspecialchars($user['customer_name']); ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="order_mobile">Mobile Number</label>
                        <input type="text" readonly name="order_mobile" id="order_mobile" class="form-control" value="<?= htmlspecialchars($user['customer_phone']); ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="order_email">Email Address</label>
                        <input type="email" readonly name="order_email" id="order_email" class="form-control" value="<?= htmlspecialchars($user['customer_email']); ?>" required>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" onchange="toggleQRCode()" class="form-control">
                            <option value="1">Cash on Delivery</option>
                            <option value="2">QR Code Payment</option>
                        </select>

                    </div>
                </div>
                <div class="col-md-12">
                    <div id="qr_code_section" style="display: none; margin-top: 10px;">
                        <div style="width: 25%;float: left;">
                            <p style="font-weight: bold;">Scan the QR Code to make the payment:</p>
                            <img src="images/interiordesign.jpeg" alt="QR Code" width="200">
                        </div>
                        <div style="width: 50%;align-items: center;padding-top: 200px;">
                            <label for="transaction_id">Transaction ID <span style="font-weight: bold;color: red;">*</span> </label>
                            <input type="text" id="transaction_id" name="transaction_id" class=" form-control" style="width: 50%;">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="shipping_address">Shipping Address</label>
                        <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" required></textarea>
                    </div>
                </div>


            </div>

            <div class="prod-page-title text-center" style="padding: 20px;">
                <h3 style="margin-bottom: 15px">Total Price: ₹<span id="grandTotal"><?= number_format($total_price, 2); ?></span></h3>
                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Place Order</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".qty").on("change", function() {
            let cart_id = $(this).data("id");
            let new_qty = $(this).val();

            $.ajax({
                url: "update_cart.php",
                type: "POST",
                data: {
                    cart_id: cart_id,
                    qty: new_qty
                },
                success: function(response) {
                    location.reload();
                }
            });
        });
    });

    function toggleQRCode() {
        var paymentMethod = document.getElementById("payment_method").value;
        var qrSection = document.getElementById("qr_code_section");

        if (paymentMethod == "2") {
            qrSection.style.display = "block"; // Show QR Code
        } else {
            qrSection.style.display = "none"; // Hide QR Code
        }
    }
</script>

<?php include 'footer.php'; // Include the footer 
?>