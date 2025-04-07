<?php
include "./config/connection.php";

include "header.php";
?>
<div class="page-content-product">
   <div class="main-product">
      <div class="container">
         <div class="row clearfix">
            <div class="find-box">
               <h1>Explore All Categories <br> Available Here.</h1>
               <h4>Find exactly what you need with ease.</h4>

            </div>
         </div>
         <div class="row clearfix">
            <?php
            $query = "SELECT * FROM `tbl_category`";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_array($result)) {
            ?>
               <div class="col-lg-4 col-sm-6 col-md-4">
                  <!-- <a href="productpage.php"> -->
                  <a href="allproducts.php?category_id=<?= $row["category_id"] ?>">
                     <div class="box-img">
                        <h4><?= $row["category_name"] ?></h4>
                        <img style="height: 250px;" src="http://localhost/interior_design/admin/uploads/categories/<?= $row['category_image'] == "" ? "defaut.png" : $row['category_image'] ?>" alt="" />
                     </div>
                  </a>
               </div>
            <?php
            }
            ?>

         </div>
      </div>
   </div>
</div>


<?php
include "footer.php";
?>