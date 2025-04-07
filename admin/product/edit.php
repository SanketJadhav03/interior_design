<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Get the product ID from the URL safely
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id === 0) {
    $_SESSION["error"] = "Invalid Product ID!";
    echo "<script>window.location = 'index.php';</script>";
    exit;
}

// Fetch product details
$productQuery = "SELECT * FROM tbl_product WHERE product_id = $product_id";
$productResult = mysqli_query($conn, $productQuery);
$product = mysqli_fetch_assoc($productResult);

if (!$product) {
    $_SESSION["error"] = "Product not found!";
    echo "<script>window.location = 'index.php';</script>";
    exit;
}

$existing_images = json_decode($product['additional_images'], true) ?? [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_update"])) {
    $product_name = mysqli_real_escape_string($conn, $_POST["product_name"]);
    $product_description = mysqli_real_escape_string($conn, $_POST["product_description"]);
    $product_price = mysqli_real_escape_string($conn, $_POST["product_price"]);
    $category_id = mysqli_real_escape_string($conn, $_POST["category_id"]);
    $product_dis = mysqli_real_escape_string($conn, $_POST["product_dis"]);
    $product_dis_value = mysqli_real_escape_string($conn, $_POST["product_dis_value"]);
    $product_status = mysqli_real_escape_string($conn, $_POST["product_status"]);
    
    // Image upload directory
    $target_dir = "../uploads/products/";

    // Handle main product image upload
    $image_path = $product['product_image']; // Default to existing image
    if (!empty($_FILES["product_image"]["name"])) {
        $product_image = time() . "_" . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $product_image;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
            $_SESSION["error"] = "Only JPG, JPEG, & PNG files are allowed!";
        } elseif ($_FILES["product_image"]["size"] > 5000000) {
            $_SESSION["error"] = "File size exceeds limit!";
        } elseif (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $image_path = $product_image;
        } else {
            $_SESSION["error"] = "Error uploading image!";
        }
    }

    // Remove selected additional images
    if (isset($_POST['remove_images'])) {
        foreach ($_POST['remove_images'] as $image) {
            $image_path_to_remove = $target_dir . $image;
            if (file_exists($image_path_to_remove)) {
                unlink($image_path_to_remove);
            }
            $existing_images = array_diff($existing_images, [$image]);
        }
    }

    // Handle additional images
    if (!empty($_FILES["additional_images"]["name"][0])) {
        foreach ($_FILES["additional_images"]["tmp_name"] as $key => $tmp_name) {
            $image_name = time() . "_" . basename($_FILES["additional_images"]["name"][$key]);
            if (move_uploaded_file($tmp_name, $target_dir . $image_name)) {
                $existing_images[] = $image_name;
            }
        }
    }

    // Convert updated images list to JSON
    $updated_images_json = json_encode(array_values($existing_images));

    // Update product details
    $updateQuery = "UPDATE tbl_product SET 
        product_name = '$product_name',
        product_description = '$product_description',
        product_price = '$product_price', 
        category_id = '$category_id',
        product_image = '$image_path',
        product_dis = '$product_dis',
        product_dis_value = '$product_dis_value',
        additional_images = '$updated_images_json',
        product_status = '$product_status'
        WHERE product_id = $product_id";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION["success"] = "Product Updated Successfully!";
        echo "<script>window.location = 'index.php';</script>";
        exit;
    } else {
        $_SESSION["error"] = "Error updating product: " . mysqli_error($conn);
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Edit Product</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold">
                        <i class="fa fa-eye"></i>&nbsp; Product List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <label for="product_name">Product Name <span class="text-danger">*</span></label>
                        <input type="text" value="<?= htmlspecialchars($product['product_name']) ?>" class="form-control" name="product_name" required>
                    </div>
                    <div class="col-6">
                        <label for="category_id">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php
                            $categoryQuery = "SELECT * FROM tbl_category WHERE category_status = 1";
                            $categoryResult = mysqli_query($conn, $categoryQuery);
                            while ($category = mysqli_fetch_assoc($categoryResult)) {
                                $selected = $product['category_id'] == $category['category_id'] ? "selected" : "";
                                echo "<option value='{$category['category_id']}' $selected>{$category['category_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-3 mt-3">
                        <label for="product_price">Product Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" value="<?= $product['product_price'] ?>" class="form-control" name="product_price" required oninput="calculateDiscountValue()">
                    </div>
                    <div class="col-3 mt-3">
                        <label for="product_dis">Product Discount (%)</label>
                        <input type="number" value="<?= $product['product_dis'] ?>" class="form-control" name="product_dis" oninput="calculateDiscountValue()">
                    </div>
                    <div class="col-3 mt-3">
                        <label for="product_dis_value">Discount Value</label>
                        <input type="number" step="0.01" value="<?= $product['product_dis_value'] ?>" class="form-control" name="product_dis_value" readonly>
                    </div>

                    <div class="col-3 mt-3">
                        <label>Product Image</label>
                        <input type="file" class="form-control" name="product_image" accept="image/*">
                        <?php if (!empty($product['product_image'])): ?>
                            <img src="../uploads/products/<?= $product['product_image'] ?>" alt="Product Image" width="100" class="mt-2">
                        <?php endif; ?>
                    </div>

                    <div class="col-12 mt-3">
                        <label>Additional Images:</label>
                        <input type="file" class="form-control" name="additional_images[]" multiple>
                        <div class="mt-3">
                            <?php foreach ($existing_images as $image) { ?>
                                <div style="display: inline-block; margin: 10px;">
                                    <img src="../uploads/products/<?= $image ?>" width="80">
                                    <br>
                                    <input type="checkbox" name="remove_images[]" value="<?= $image ?>"> Remove
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="product_description">Product Description</label>
                        <textarea rows="5" class="form-control" name="product_description"><?= htmlspecialchars($product['product_description']) ?></textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button name="product_update" type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </div>
    </form>
</div>

<?php include "../component/footer.php"; ?>
