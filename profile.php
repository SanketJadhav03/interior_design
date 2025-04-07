<?php
include "config/connection.php";
include("header.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$query = "SELECT * FROM tbl_customer WHERE customer_id = '$customer_id'";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

if (!$customer) {
    echo "<p class='text-center text-danger'>Customer not found!</p>";
    exit();
}

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
    $customer_address = mysqli_real_escape_string($conn, $_POST['customer_address']);
    
    // Handle Profile Image Upload
    if (!empty($_FILES["customer_image"]["name"])) {
        $image_name = time() . "_" . $_FILES["customer_image"]["name"];
        $image_tmp = $_FILES["customer_image"]["tmp_name"];
        $image_path = "admin/uploads/customers/" . $image_name;
        
        move_uploaded_file($image_tmp, $image_path);
        $update_image = ", customer_image = '$image_name'";
    } else {
        $update_image = "";
    }

    // Update Query
    $updateQuery = "UPDATE tbl_customer SET 
                    customer_name = '$customer_name',
                    customer_email = '$customer_email',
                    customer_phone = '$customer_phone',
                    customer_address = '$customer_address'
                    $update_image
                    WHERE customer_id = '$customer_id'";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Profile Updated Successfully!'); window.location='profile.php';</script>";
    } else {
        echo "<p class='text-danger text-center'>Error updating profile: " . mysqli_error($conn) . "</p>";
    }
}
?>
<div class="product-page-main"> 
<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card shadow-lg p-4">
            <div class="row clearfix">
                        <div class="prod-page-title text-center " style="padding: 40px;">
                           <h1 class="text-center text-primary mb-4">Customer Profile   </h1>
                        </div>
                    </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="text-center mb-3">
                        <img src="admin/uploads/customers/<?= $customer['customer_image'] ?: 'default.png'; ?>" 
                             class="rounded-circle" 
                             style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #007bff;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="customer_name" class="form-control" value="<?= $customer['customer_name']; ?>" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label class="form-label">Email</label>
                        <input type="email" name="customer_email" class="form-control" value="<?= $customer['customer_email']; ?>" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label class="form-label">Phone</label>
                        <input type="text" name="customer_phone" class="form-control" value="<?= $customer['customer_phone']; ?>" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label class="form-label">Address</label>
                        <textarea name="customer_address" class="form-control" required><?= $customer['customer_address']; ?></textarea>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="customer_image" class="form-control">
                    </div>
 
                    <div class="prod-page-title text-center " style="padding: 40px;">
                       <button type="submit" class="btn btn-success w-100"> <i class="fa fa-user"></i> Update Profile</button> 
                       <a href="index.php" class="btn btn-primary"> <i class="fa fa-home"></i> Back to Dashboard</a>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div> 
</div>

<?php include("footer.php"); ?>
