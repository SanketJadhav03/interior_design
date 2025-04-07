<footer>
   <div class="main-footer">
      <div class="container">
         <div class="row">
            <div class="footer-link-box clearfix">
               <div class="col-md-6 col-sm-6">
                  <div class="left-f-box">
                     <div class="col-sm-6">
                        <h2>INTERIOR DESIGN</h2>
                        <ul>
                           <li> <a href="">"A well-designed space enhances comfort, functionality, and aesthetics."</a> </li>
                           <li> <a href="">

                              "Interior design transforms a house into a personalized sanctuary."
                           </a>
                           </li>
                           <li> <a href=""> 

                              "Thoughtful color palettes and textures create a harmonious atmosphere."
                           </a>
                           </li> 
                        </ul>

                     </div>

                     <div class="col-sm-6">
                        <h2> </h2>
                        <ul>
                           <li><a href="index.php">Home</a></li>
                           <li><a href="allproducts.php">Our Products</a></li>
                           <li><a href="allproducts.php">Our Categories</a></li>
                           <li><a href="about-us.php">About US</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="col-md-6 col-sm-6">
                  <div class="right-f-box">
                     <h2> </h2>
                     <?php
                     $query = "SELECT category_name FROM tbl_category WHERE category_status = 1 ORDER BY category_name ASC";
                     $result = $conn->query($query);

                     $categories = [];
                     while ($row = $result->fetch_assoc()) {
                        $categories[] = $row['category_name'];
                     }

                     $conn->close();

                     // Split categories into 3 columns
                     $columnSize = ceil(count($categories) / 3);
                     $columns = array_chunk($categories, $columnSize);
                     ?>

                     <ul class="col-sm-6">
                        <?php foreach ($columns[0] as $category) : ?>
                           <li><a href="#"><?= htmlspecialchars($category) ?></a></li>
                        <?php endforeach; ?>
                     </ul>
                     <ul class="col-sm-6">
                        <?php foreach ($columns[1] as $category) : ?>
                           <li><a href="#"><?= htmlspecialchars($category) ?></a></li>
                        <?php endforeach; ?>
                        <li><a href="allcategories.php">See all here</a></li> <!-- Link to all categories -->
                     </ul>


                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="copyright">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-8">
               <p>
                  Interior Design All Rights Reserved. </p>

            </div>
            <div class="col-md-4">
               <ul class="list-inline socials">
                  <li>
                     <a href="">
                        <i class="fa fa-facebook" aria-hidden="true"></i>
                     </a>
                  </li>
                  <li>
                     <a href="">
                        <i class="fa fa-twitter" aria-hidden="true"></i>
                     </a>
                  </li>
                  <li>
                     <a href="">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                     </a>
                  </li>
                  <li>
                     <a href="#">
                        <i class="fa fa-linkedin" aria-hidden="true"></i>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</footer>
<!--main js-->
<script src="js/jquery-1.12.4.min.js"></script>
<!--bootstrap js-->
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/slick.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/wow.min.js"></script>
<!--custom js-->
<script src="js/custom.js"></script>
</body>

</html>