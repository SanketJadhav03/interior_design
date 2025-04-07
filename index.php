<?php
include "./config/connection.php";

include "header.php";
?>
<div class="page-content-product">
   <div class="main-product">
      <div class="container">
         <div class="row clearfix">
            <div class="find-box">
               <h1>Find your next product  <br>    here.</h1>
               <h4>It has never been easier.</h4>  
            </div>
         </div>
         <div class="row clearfix"> 
             <?php
             $query = "SELECT * FROM `tbl_category` LIMIT 6";
             $result = mysqli_query($conn,$query);
             while($row = mysqli_fetch_array($result)){
             ?>
            <div class="col-lg-4 col-sm-6 col-md-4">
               <!-- <a href="productpage.php"> -->
               <a href="allproducts.php?category_id=<?= $row["category_id"] ?>">
                  <div class="box-img">
                     <h4><?= $row["category_name"] ?></h4>
                     <img style="height: 250px;" src="http://localhost/interior_design/admin/uploads/categories/<?= $row['category_image'] == "" ? "defaut.png" :$row['category_image'] ?>" alt="" />
                  </div>
               </a>
            </div>
            <?php
             }
            ?>
            <div class="categories_link">
               <a href="allcategories.php">Browse all categories here</a>
            </div>
         </div>
      </div>
   </div>
</div>
 
<div class="products_exciting_box">
   <div class="container">
      <div class="row">
         <div class="col-md-6 col-sm-6 wow fadeIn" data-wow-delay="0.2s">
            <div class="exciting_box f_pd">
               <img src="images/exciting_img-01.jpg" class="icon-small" alt="" />
               <h4>Explore <strong>exciting</strong> and exotic products
                  tailored to you.
               </h4>
               <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                  quis nostrud exercitation ullamco laboris..
               </p>
            </div>
         </div>
         <div class="col-md-6 col-sm-6 wow fadeIn" data-wow-delay="0.4s">
            <div class="exciting_box l_pd">
               <img src="images/exciting_img-02.jpg" class="icon-small" alt="" />
               <h4><strong>List your products on</strong> chamb and grow connections.</h4>
               <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                  quis nostrud exercitation ullamco laboris..
               </p>
            </div>
         </div>
      </div>
   </div>
</div>
  
<?php
include "footer.php";
?>

