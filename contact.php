<?php
$title = "Contact Us";
include "config/connection.php";
include("header.php");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);
    $created_at = date("Y-m-d H:i:s");

    $insertQuery = "INSERT INTO tbl_contact (contact_name, contact_email, contact_phone, contact_message, created_at) 
                    VALUES ('$name', '$email', '$phone', '$message', '$created_at')";

    if (mysqli_query($conn, $insertQuery)) {
        $successMessage = "Your message has been sent successfully!";
    } else {
        $errorMessage = "There was an error sending your message. Please try again.";
    }
}
?>

<div class="product-page-main">
    <div class="content-wrapper">
        <!-- Hero Section -->
            <div class="row clearfix">
                <div class="prod-page-title text-center " style="padding: 40px;">
                    <h1  >Get in Touch</h1>
                    <p class="" style="font-weight: bold;margin-top: 10px;">We're here to help! Drop us a message, and we'll respond as soon as possible.</p>
                </div>
            </div>

        <!-- Contact Form Section -->
        <section class="contact-form-section py-5">
            <div class="container">
                <div class="row">
                    <!-- Contact Form -->
                    <div class="col-md-6">
                        <h3 class="text-primary mb-4" style="font-weight: bold;">Send Us a Message</h3>
                        <p  style="font-weight: bold;margin-top: 10px;" class="text-muted mb-4">Fill out the form below, and our team will reach out to you shortly.</p>

                        <!-- Display Success or Error Messages -->
                        <?php if (isset($successMessage)) { ?>
                           
                                <?= $successMessage ?>
                               
                        <?php } elseif (isset($errorMessage)) { ?>
                             
                                <?= $errorMessage ?>  
                        <?php } ?>

                        <form action="" method="POST" class="card p-4 shadow">
                            <div class="mb-3" style="font-weight: bold;padding: 15px;">
                                <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
                            </div>
                            <div class="mb-3" style="font-weight: bold;padding: 15px;">
                                <label for="phone" class="form-label fw-bold">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" required placeholder="Enter your phone number">
                            </div>
                            <div class="mb-3" style="font-weight: bold;padding: 15px;">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email address">
                            </div>
                            <div class="mb-3" style="font-weight: bold;padding: 15px;">
                                <label for="message" class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Write your message here..."></textarea>
                            </div>
                            <div style="font-weight: bold;padding: 15px;">

                                <button  type="submit" class="btn btn-primary w-100">Send Message</button>
                            </div>
                        </form>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-md-6">
                        <h3 class="text-primary mb-4" style="font-weight: bold;">Contact Information</h3>
                        <p class="text-muted" style="font-weight: bold;padding: 15px;">
                            Whether you have a question, need assistance, or just want to say hello, feel free to reach out to us.
                        </p>

                        

                        <!-- Google Maps -->
                        <div class="map-section mt-4">
                            <h4 class="text-primary">Find Us</h4>
                            <p class="text-muted">Click on the map for directions.</p>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=YOUR_GOOGLE_MAPS_EMBED_URL_HERE" allowfullscreen=""></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Thank You Section -->
        <section class="thank-you-section py-5 bg-light text-center" style="font-weight: bold;margin-top: 30px;">
            <h3 class="font-weight-bold text-primary">We Appreciate Your Message!</h3>
            <p class="text-muted" style="font-weight: bold;padding: 15px;">Thank you for reaching out. We will get back to you as soon as possible.</p>
        </section>
    </div>
</div>

<?php
include("footer.php");
?>