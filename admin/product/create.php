<?php

include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Check if the form is submitted
if (isset($_POST["product_create"])) {
    // Sanitize and get form data
    $product_name = trim($_POST["product_name"]);
    $product_description = trim($_POST["product_description"]);
    $product_price = trim($_POST["product_price"]);
    $category_id = trim($_POST["category_id"]);
    $product_dis = trim($_POST["product_dis"]);
    $product_dis_value = trim($_POST["product_dis_value"]);
    $product_status = 1; // Active by default

    // Validate required fields
    if (empty($product_name) || empty($product_price) || empty($category_id)) {
        $_SESSION["error"] = "Please fill in all required fields!";
        header("Location: create.php");
        exit();
    }

    // Handle main product image
    $image_path = null;
    if (!empty($_FILES["product_image"]["name"])) {
        $target_dir = "../uploads/products/";
        $image_extension = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));
        $allowed_extensions = ["jpg", "jpeg", "png"];

        // Validate image type
        if (!in_array($image_extension, $allowed_extensions)) {
            $_SESSION["error"] = "Only JPG, JPEG, & PNG files are allowed!";
            header("Location: create.php");
            exit();
        }

        // Validate image size (max 5MB)
        if ($_FILES["product_image"]["size"] > 5000000) {
            $_SESSION["error"] = "File size must be less than 5MB!";
            header("Location: create.php");
            exit();
        }

        // Generate unique file name
        $unique_filename = time() . "_" . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $unique_filename;

        // Move uploaded file
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $image_path = $unique_filename;
        } else {
            $_SESSION["error"] = "Error uploading the image!";
            header("Location: create.php");
            exit();
        }
    }

    // Handle multiple additional images
    $additional_images = [];
    if (!empty($_FILES["additional_images"]["name"][0])) {
        foreach ($_FILES["additional_images"]["tmp_name"] as $key => $tmp_name) {
            $image_name = time() . "_" . $_FILES["additional_images"]["name"][$key];
            $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            if (in_array($image_extension, ["jpg", "jpeg", "png"])) {
                move_uploaded_file($tmp_name, "../uploads/products/" . $image_name);
                $additional_images[] = $image_name;
            }
        }
    }

    // Convert additional images array to JSON
    $additional_images_json = json_encode($additional_images);

    // Insert data into database using prepared statements
    $stmt = $conn->prepare("INSERT INTO tbl_product 
        (product_name, product_description, product_price, category_id, product_image, product_dis, product_dis_value, product_status, additional_images) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssdisddis", $product_name, $product_description, $product_price, $category_id, $image_path, $product_dis, $product_dis_value, $product_status, $additional_images_json);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Product Created Successfully!";
        echo "<script>window.location = 'index.php';</script>";
        exit();
    } else {
        $_SESSION["error"] = "Error creating product: " . $stmt->error;
        header("Location: create.php");
        exit();
    }
}

?>

<div class="content-wrapper p-2">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Create Product</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold">
                        <i class="fa fa-eye"></i>&nbsp; Product List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <label for="product_name">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" name="product_name" id="product_name" required>
                    </div>
                    <div class="col-6">
                        <label for="category_id">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control font-weight-bold" required>
                            <option value="">Select Category</option>
                            <?php
                            $categoryQuery = "SELECT * FROM tbl_category WHERE category_status = 1";
                            $categoryResult = mysqli_query($conn, $categoryQuery);
                            while ($category = mysqli_fetch_assoc($categoryResult)) {
                                echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-3 mt-3">
                        <label for="product_price">Product Price <span class="text-danger">*</span></label>
                        <input type="number" class="form-control font-weight-bold" name="product_price" id="product_price" required oninput="calculateDiscountValue()">
                    </div>
                    <div class="col-3 mt-3">
                        <label for="product_dis">Product Discount (%)</label>
                        <input type="number" class="form-control font-weight-bold" name="product_dis" id="product_dis" oninput="calculateDiscountValue()">
                    </div>
                    <div class="col-3 mt-3">
                        <label for="product_dis_value">Discount Value</label>
                        <input type="number" class="form-control font-weight-bold" name="product_dis_value" id="product_dis_value" readonly>
                    </div>

                    <div class="col-3 mt-3">
                        <label for="product_image">Product Image</label>
                        <input type="file" class="form-control font-weight-bold" name="product_image" id="product_image" accept="image/*">
                    </div>
                    <div class="col-12 mt-3">
                        <label>Additional Images (Select multiple):</label>
                        <input type="file" class="form-control" name="additional_images[]" multiple>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="product_description">Product Description</label>
                        <textarea rows="5" name="product_description" id="product_description" class="form-control font-weight-bold"></textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button name="product_create" type="submit" class="btn btn-primary shadow font-weight-bold">
                    <i class="fa fa-save"></i>&nbsp; Create Product
                </button>
                <button type="reset" class="btn btn-danger shadow font-weight-bold">
                    <i class="fas fa-times"></i>&nbsp; Clear
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function calculateDiscountValue() {
    const price = parseFloat(document.getElementById("product_price").value);
    const discount = parseFloat(document.getElementById("product_dis").value);
    if (!isNaN(price) && !isNaN(discount)) {
        document.getElementById("product_dis_value").value = ((price * discount) / 100).toFixed(2);
    } else {
        document.getElementById("product_dis_value").value = '';
    }
}
</script>

<?php include "../component/footer.php"; ?>
