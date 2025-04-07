<?php
session_start();
if (!isset($_SESSION["customer_id"])) {
   echo "<script>window.location.href = 'login.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>Interior Design</title>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!--enable mobile device-->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!--fontawesome css-->
   <link rel="stylesheet" href="css/font-awesome.min.css">
   <!--bootstrap css-->
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <!--animate css-->
   <link rel="stylesheet" href="css/animate-wow.css">
   <!--main css-->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/bootstrap-select.min.css">
   <link rel="stylesheet" href="css/slick.min.css">
   <link rel="stylesheet" href="css/jquery-ui.css">
   <!--responsive css-->
   <link rel="stylesheet" href="css/responsive.css">
</head>

<body>
   <header id="header" class="top-head">
      <nav class="navbar navbar-default">
         <div class="container-fluid">
            <div class="row">
               <div class="col-md-3 col-sm-12 left-rs">
                  <div class="navbar-header">
                     <button type="button" id="top-menu" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                     </button>
                     <a href="index.php" class="navbar-brand">INTERIOR DESIGN</a>
                  </div>
               </div>
               <div class="col-md-9 col-sm-12">
                  <div class="right-nav">
                     <div class="login-sr">
                        <div class="login-signup">
                           <ul>
                              <li><a class="custom-b" href="profile.php">
                                    <?= $_SESSION["customer_name"] ?>
                                 </a></li>
                              <li>
                                 <a class="custom-b p-0" href="#" data-toggle="modal" data-target="#wishlistModal">
                                    <i class="fa fa-heart"></i>
                                    <sup class="badge wishlist-count">0</sup> <!-- Wishlist count here -->
                                 </a>
                              </li>
                              <li>
                                 <a class="custom-b p-0" href="#" data-toggle="modal" data-target="#cartModal">
                                    <i class="fa fa-shopping-cart"></i>
                                    <sup class="badge cart-count">0</sup> <!-- Cart count here -->
                                 </a>
                              </li>

                           </ul>
                        </div>
                     </div>
                     <div class="nav-b hidden-xs">
                        <div class="nav-box">
                           <ul>
                              <li><a style="font-weight: bold;" href="index.php"><i class="fa fa-home"></i>&nbsp; Home</a></li>
                              <li><a style="font-weight: bold;" href="allcategories.php"> <i class="fa fa-list"></i>&nbsp; Categories</a></li>
                              <li><a style="font-weight: bold;" href="allproducts.php"><i class="fa fa-tags"></i>&nbsp; Products</a></li>
                              <li><a style="font-weight: bold;" href="about-us.php"><i class="fa fa-info"></i>&nbsp; About Us</a></li>
                              <li><a style="font-weight: bold;" href="about-us.php"><i class="fa fa-envelope"></i>&nbsp; Contact</a></li>
                              <li><a style="font-weight: bold;" href="history.php"><i class="fa fa-shopping-bag"></i>&nbsp;Order History</a></li>
                              <li><a style="font-weight: bold;" href="logout.php"><i class="fa fa-sign-out"></i>&nbsp; Logout</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </nav>
   </header>

   <!-- Wishlist Modal -->
   <div id="wishlistModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Your Wishlist</h4>
            </div>
            <div class="modal-body">
               <ul id="wishlist-items" class="list-group">
                  <!-- Wishlist items will be loaded dynamically -->
               </ul>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" id="clearWishlist">Clear Wishlist</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>

   <!-- Shopping Cart Modal -->
   <div id="cartModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Your Cart</h4>
            </div>
            <div class="modal-body">
               <ul id="cart-items" class="list-group">
                  <!-- Cart items will be loaded dynamically -->
               </ul>
            </div>
            <div class="modal-footer">
               <a href="checkout_confirmation.php" class="btn btn-success">Proceed to Checkout</a>
               <button type="button" class="btn btn-danger" id="clearCart">Clear Cart</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>


   <!-- Bootstrap & jQuery Scripts -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <script>
      $(document).ready(function() {
         function loadWishlist() {
            $.ajax({
               url: 'load_wishlist.php',
               type: 'GET',
               dataType: 'html',
               success: function(response) {

                  $('#wishlist-items').html(response);
               },
               error: function() {
                  $('#wishlist-items').html('<li class="list-group-item text-danger">Error loading wishlist.</li>');
               }
            });

         }

         $('#wishlistModal').on('show.bs.modal', function() {
            loadWishlist();
         });

         $(document).on('click', '.remove-wishlist', function() {
            let wishlistId = $(this).data("id");
            $.ajax({
               url: 'remove_wishlist.php',
               type: 'POST',
               data: {
                  wishlist_id: wishlistId
               },
               success: function(response) {
                  alert("Removed Successfully!");
                  loadWishlist(); // Reload wishlist after removing
               }
            });
         });
         $(document).on('click', '#clearWishlist', function() {
            $.ajax({
               url: 'clear_wishlist.php',
               type: 'POST',
               success: function(response) {
                  alert("Wishlist Clear Successfully!");
                  loadWishlist(); // Reload wishlist after removing
               }
            });
         });
      });
      $(document).ready(function() {
         function updateCounts() {
            $.ajax({
               url: 'count_wishlist_cart.php',
               type: 'GET',
               dataType: 'json',
               success: function(response) {
                  $(".wishlist-count").text(response.wishlist_count);
                  $(".cart-count").text(response.cart_count);
               }
            });
         }

         // Call function on page load
         updateCounts();

         // Refresh counts when wishlist/cart modal opens
         $('#wishlistModal, #cartModal').on('show.bs.modal', updateCounts);
      });

      $(document).ready(function() {
         function loadCart() {
            $.ajax({
               url: 'load_cart.php',
               type: 'GET',
               dataType: 'json',
               success: function(response) {
                  let cartHtml = "";
                  if (response.status === "success") {
                     response.cart_items.forEach(item => {
                        cartHtml += `
                            <li  style='display: flex; justify-content: space-between;font-size: 16px' class='list-group-item wishlist-item d-flex justify-content-between align-items-center p-3 border rounded shadow-sm'>
                                <span>${item.product_name} (x ${item.cart_product_qty}) - ₹${item.total_price}</span>
                                <button class="btn btn-sm btn-danger remove-cart" data-id="${item.cart_id}">Remove</button>
                            </li>`;
                     });
                  } else {
                     cartHtml = `<li class="list-group-item text-warning">${response.message}</li>`;
                  }
                  $("#cart-items").html(cartHtml);
               }
            });
         }


         $(document).on('click', '.remove-cart', function() {
            let wishlistId = $(this).data("id");
            $.ajax({
               url: 'remove_cart.php',
               type: 'POST',
               data: {
                  cart_id: wishlistId
               },
               success: function(response) {
                  alert("Removed Successfully!");
                  loadCart(); // Reload wishlist after removing
               }
            });
         });
         $(document).on('click', '#clearCart', function() {
            $.ajax({
               url: 'clear_cart.php',
               type: 'POST',
               success: function(response) {
                  alert("Cart Clear Successfully!");
                  loadCart(); // Reload wishlist after removing
               }
            });
         });
         $('#cartModal').on('show.bs.modal', loadCart);
      });
   </script>