<?php
include "config/connection.php";
include "header.php";

// Fetch all categories
if (isset($_GET["category_id"])) {
    $category_id = $_GET["category_id"];
    $categoryQuery = "SELECT * FROM `tbl_category` WHERE `category_id` = '$category_id'";
} else {
    $categoryQuery = "SELECT * FROM `tbl_category`";
}
$categoryResult = mysqli_query($conn, $categoryQuery);
?>

<div class="product-page-main">
    <div class="container">

        <!-- Loop through each category -->
        <?php while ($category = mysqli_fetch_array($categoryResult)) {
            // Fetch products for this category
            $category_id = $category['category_id'];
            $productQuery = "SELECT * FROM `tbl_product` WHERE category_id = '$category_id'";
            $productResult = mysqli_query($conn, $productQuery);

            // Check if category has products
            if (mysqli_num_rows($productResult) > 0) { ?>
                <div class="row clearfix">
                    <div class="row clearfix">
                        <div class="prod-page-title text-center " style="padding: 60px;">
                            <h1 class=""><?= $category["category_name"] ?> </h1>
                        </div>
                    </div>
                    <div class="row product-box-main ">
                        <?php while ($product = mysqli_fetch_array($productResult)) { ?>
                            <div class="col-md-4 mb-3 ">
                                <div class="small-box-c">
                                    <div class="small-img-b">
                                        <img
                                            style="height: 250px;"
                                            src="http://localhost/interior_design/admin/uploads/products/<?= !empty($product['product_image']) ? $product['product_image'] : "default.png" ?>"
                                            class="img-responsive" alt="" />
                                    </div>
                                    <div class="dit-t clearfix">
                                        <div class="left-ti">
                                            <h4><?= $product["product_name"] ?></h4>
                                        </div>
                                        <div class="right-ti">
                                            <a href="product_details.php?product_id=<?= $product['product_id'] ?>" class="btn btn-primary">View Details</a>
                                        </div>
                                    </div>

                                    <div style="display: flex; justify-content: space-between;font-size: 15px; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
                                        <p><strong>Dis: </strong> <?= $product["product_dis"] ?>%</p>
                                        <del style="font-weight: bold;">Rs <?= $product["product_price"] ?></del>
                                        <p style="font-weight: bold;"><strong>Rs </strong> <?= $product["product_price"] - $product["product_dis_value"] ?></p>
                                    </div>
                                    <div class="prod-btn text-center">
                                        <a href="javascript:void(0);" class="wishlist-btn" data-id="<?= $product['product_id'] ?>">
                                            <i class="fa fa-star"></i> Save to wishlist
                                        </a>
                                        <a href="javascript:void(0);" class="cart-btn" data-id="<?= $product['product_id'] ?>">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    </div>

                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <hr> <!-- Separator for better visibility -->
        <?php }
        } ?>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
    // Add to Wishlist
    $(".wishlist-btn").click(function () {
        var product_id = $(this).data("id");
        
        $.ajax({
            url: "add_to_wishlist.php",
            type: "POST",
            data: { product_id: product_id },
            dataType: "json",
            success: function (response) {
                alert(response.message);
                window.location.reload();
            }
        });
    });

    // Add to Cart
    $(".cart-btn").click(function () {
        var product_id = $(this).data("id");
        
        $.ajax({
            url: "add_to_cart.php",
            type: "POST",
            data: { product_id: product_id },
            dataType: "json",
            success: function (response) {
                alert(response.message);
                window.location.reload();
            }
        });
    });
});

</script>

<?php include "footer.php"; ?>