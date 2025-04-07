<?php
include "header.php";
include "config/connection.php";

$product_id = $_GET["product_id"];
$sql = "SELECT * FROM tbl_product INNER JOIN tbl_category ON tbl_category.category_id = tbl_product.category_id WHERE product_id = $product_id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>
<div class="product-page-main " style="padding: 10px;">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
            <div class="prod-page-title">
               <h2 style="font-weight: bold;"><?= $row["product_name"] ?></h2>
               <p>From <span><?= $row["category_name"] ?></span></p>
            </div>
         </div>
      </div>
      <div class="row">

         <div class="col-md-8 col-sm-8">
            <div class="md-prod-page">
               <div class="md-prod-page-in">
                  <div class="page-preview">
                     <div class="preview">
                        <div class="preview-pic tab-content">
                           <?php
                           $additional_images = json_decode($row['additional_images'], true);
                           if (!empty($additional_images)) { ?>
                              <?php foreach ($additional_images as $index => $image) { ?>
                                 <div class="tab-pane <?= $index == 0 ? 'active' : ''  ?> " id="pic-<?= $index ?>"><img style="height: 500px;" src="admin/uploads/products/<?= $image; ?>" alt="#" /></div>
                              <?php } ?>
                           <?php } else { ?>
                              <p>No additional images available.</p>
                           <?php } ?>
                        </div>
                        <div class="preview-thumbnail nav nav-tabs" style="display: flex; flex-wrap: wrap; ">
                           <?php if (!empty($additional_images)) { ?>
                              <?php foreach ($additional_images as $index => $image) { ?>
                                 <div style="width: 20%; text-align: center;padding: 5px;">
                                    <a data-target="#pic-<?= $index ?>" data-toggle="tab">
                                       <img style="height: 200px;" src="admin/uploads/products/<?= $image; ?>" alt="#" />
                                    </a>
                                 </div>
                              <?php } ?>
                           <?php } else { ?>
                              <p>No additional images available.</p>
                           <?php } ?>
                        </div>

                     </div>
                  </div>

               </div>
               <div class="description-box">
                  <div class="dex-a">
                     <h4>Description</h4>
                     <p>
                        <?= $row["product_description"] ?>
                     </p>
                  </div>
                  <hr>
                  <div>
                     <?php
                     $ratingQuery = "SELECT r.rating, r.review_rating, r.created_at, c.customer_name 
                FROM tbl_ratings r 
                INNER JOIN tbl_customer c ON r.customer_id = c.customer_id 
                WHERE r.product_id = $product_id";

                     $ratingResult = $conn->query($ratingQuery);

                     ?>

                     <style>
                        .reviews-section {
                           max-width: 900px;
                           margin: 20px auto;
                           text-align: center;
                        }

                        .reviews-container {
                           display: flex;
                           justify-content: center;
                           gap: 15px;
                           flex-wrap: wrap;
                        }

                        .review-box {
                           background: #ffffff;
                           border-radius: 10px;
                           padding: 15px;
                           width: 48%;
                           border-left: 4px solid #ffcc00;
                           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                           transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
                        }

                        .review-box:hover {
                           transform: translateY(-3px);
                           box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
                        }

                        .review-box h4 {
                           font-size: 16px;
                           color: #333;
                           margin-bottom: 5px;
                           font-weight: 600;
                        }

                        .rating-stars {
                           color: #ffcc00;
                           font-size: 16px;
                           margin-bottom: 5px;
                        }

                        .review-text {
                           font-size: 14px;
                           color: #555;
                           margin-bottom: 8px;
                           line-height: 1.4;
                        }

                        small {
                           font-size: 12px;
                           color: #777;
                           display: block;
                        }

                        /* Responsive Design */
                        @media (max-width: 600px) {
                           .reviews-container {
                              flex-direction: column;
                           }

                           .review-box {
                              width: 90%;
                              margin: 0 auto;
                           }
                        }
                     </style> 
                     <h3>Customer Reviews</h3>
                     <div class="reviews-section">
                        <div class="reviews-container">
                           <?php
                           if ($ratingResult->num_rows > 0) {
                              while ($ratingRow = $ratingResult->fetch_assoc()) { ?>
                                 <div class="review-box">
                                    <h4><?= htmlspecialchars($ratingRow["customer_name"]) ?></h4>
                                    <p class="rating-stars">
                                       <?php for ($i = 1; $i <= 5; $i++) {
                                          echo ($i <= $ratingRow["rating"]) ? "★" : "☆";
                                       } ?>
                                    </p>
                                    <p class="review-text"><?= nl2br(htmlspecialchars($ratingRow["review_rating"])) ?></p>
                                    <small>Reviewed on: <?= date("d/m/Y", strtotime($ratingRow["created_at"])) ?></small>
                                 </div>
                              <?php }
                           } else { ?>
                              <p>No reviews yet.</p>
                           <?php } ?>
                        </div>
                     </div>



                  </div>
               </div>
            </div>

         </div>
         <div class="col-md-4 col-sm-12">
            <div class="price-box-right">
               <h3> MRP <del class="h4"><?= $row["product_price"] ?></del></h3>
               <h3> Dis <strong class="h4"><?= $row["product_dis"]  ?>%</strong></h3>
               <h3> Sale Price <strong class="h4"><?= $row["product_price"] - $row["product_dis_value"] ?></strong></h3>
               <a href="javascript:void(0);" class="wishlist-btn" data-id="<?= $row['product_id'] ?>"> <i class="fa fa-heart"></i> &nbsp; Add To Wishlist</a>
               <a href="javascript:void(0);" class="cart-btn" data-id="<?= $row['product_id'] ?>"> <i class="fa fa-shopping-cart"></i> &nbsp; Add To Cart</a>
            </div>

         </div>
         <div class="col-12">
            <div class="row  ">
               <?php
               // Fetch products for this category
               $category_id = $row['category_id'];
               $productQuery = "SELECT * FROM `tbl_product` WHERE category_id = '$category_id' and product_id != $product_id";
               $productResult = mysqli_query($conn, $productQuery);

               // Check if category has products
               if (mysqli_num_rows($productResult) > 0) {
                  while ($product = mysqli_fetch_array($productResult)) { ?>
                     <div class="col-md-4  " style="margin-top: 15px;">
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

                           <div style="display: flex; justify-content: space-between;">
                              <p><strong>Dis: </strong> <?= $product["product_dis"] ?>%</p>
                              <del style="font-weight: bold;">MRP: <?= $product["product_price"] ?></del>
                              <p><strong>Price: </strong> <?= $product["product_price"] - $product["product_dis_value"] ?></p>
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
               <?php }
               } ?>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
   $(document).ready(function() {
      // Add to Wishlist
      $(".wishlist-btn").click(function() {
         var product_id = $(this).data("id");

         $.ajax({
            url: "add_to_wishlist.php",
            type: "POST",
            data: {
               product_id: product_id
            },
            dataType: "json",
            success: function(response) {
               alert(response.message);
               window.location.reload();
            }
         });
      });

      // Add to Cart
      $(".cart-btn").click(function() {
         var product_id = $(this).data("id");

         $.ajax({
            url: "add_to_cart.php",
            type: "POST",
            data: {
               product_id: product_id
            },
            dataType: "json",
            success: function(response) {
               alert(response.message);
               window.location.reload();
            }
         });
      });
   });
</script>

<?php
include "footer.php";
?>