<?php
include "./config/connection.php";

try {
    // Validate required fields
    if (empty($_POST['customer_name']) || empty($_POST['customer_email']) || empty($_POST['customer_phone']) || empty($_POST['customer_password'])) {
        throw new Exception("All fields are required");
    }

    // Sanitize inputs
    $customer_name = htmlspecialchars(trim($_POST["customer_name"]));
    $customer_email = filter_var($_POST["customer_email"], FILTER_SANITIZE_EMAIL);
    $customer_phone = preg_replace('/[^0-9]/', '', $_POST["customer_phone"]);
    
    // Validate email format
    if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Validate phone number (10 digits)
    if (!preg_match('/^[0-9]{10}$/', $customer_phone)) {
        throw new Exception("Phone number must be 10 digits");
    }

    // Validate password strength (optional)
    if (strlen($_POST["customer_password"]) < 8) {
        throw new Exception("Password must be at least 8 characters");
    }

    // Hash password
    $hashed_password = password_hash($_POST["customer_password"], PASSWORD_BCRYPT);
    
    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO `tbl_customer`(`customer_name`, `customer_email`, `customer_password`, `customer_phone`) 
                           VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $customer_name, $customer_email, $hashed_password, $customer_phone);
    
    if ($stmt->execute()) {
        echo "<script>
              alert('Registration successful!');
              window.location.href = 'login.php';
              </script>";
    } else {
        throw new Exception("Registration failed: " . $stmt->error);
    }

} catch (Exception $e) {
    echo "<script>
          alert('Error: " . addslashes($e->getMessage()) . "');
          window.location.href = 'register.php';
          </script>";
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>