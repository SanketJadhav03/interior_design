<?php
$conn = mysqli_connect("localhost", "root", "", "interior_design");

if (!$conn) {
    echo "<script>alert('Something Went Wrong!')</script>";
} else { 
    mysqli_query($conn, "SET time_zone = '+05:30'");
}
?>
