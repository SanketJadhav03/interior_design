<?php
include "config/connection.php";
include "component/header.php";
include "component/sidebar.php";

$adminId = $_SESSION["admin_id"];
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $mobile_number = $_POST["mobile_number"];
    $password = $_POST["password"];
    $updated_at = date('Y-m-d H:i:s'); // Current time in UTC

    // Update query based on whether a new password is provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE admins SET username = '$username', email = '$email', mobile_number = '$mobile_number', password = '$hashedPassword' WHERE id = $adminId";
    } else {
        $updateQuery = "UPDATE admins SET username = '$username', email = '$email', mobile_number = '$mobile_number' WHERE id = $adminId";
    }

    // Execute the update query and set the message
    if (mysqli_query($conn, $updateQuery)) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
    }
}

// Fetch current admin data to display in the form
$query = "SELECT * FROM admins WHERE id = $adminId";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);
?>

<div class="content-wrapper">
    <div class="container pt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-light  text-white text-center">
                <h3 class="font-weight-bold">Update Profile</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>
                <form action="" method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username:</label>
                        <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email:</label>
                        <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="mobile_number" class="form-label">Mobile Number:</label>
                        <input class="form-control" type="text" name="mobile_number" value="<?php echo htmlspecialchars($admin['mobile_number']); ?>" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="password" class="form-label">Password:</label>
                        <input class="form-control" type="password" name="password" placeholder="Enter new password (optional)">
                    </div>
                    


                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success mt-4">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include "component/footer.php";
?>