<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Design by foolishdeveloper.com -->
    <title>User Login</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!--Stylesheet-->
    <style media="screen">
      *,
*:before,
*:after{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
body{
    background-color: #080710;
}
.background{
    width: 430px;
    height: 520px;
    position: absolute;
    transform: translate(-50%,-50%);
    left: 50%;
    top: 50%;
}
.background .shape{
    height: 200px;
    width: 200px;
    position: absolute;
    border-radius: 50%;
}
.shape:first-child{
    background: linear-gradient(
        #1845ad,
        #23a2f6
    );
    left: -80px;
    top: -80px;
    animation: float 6s infinite alternate ease-in-out;
}
.shape:last-child{
    background: linear-gradient(
        to right,
        #ff512f,
        #f09819
    );
    right: -30px;
    bottom: -80px;
    animation: float-1 6s infinite alternate ease-in-out;
}

@keyframes float {
    from { transform: translateY(-50px); }
    to { transform: translateY(580px); }
}
@keyframes float-1 {
    from { transform: translateY(50px); }
    to { transform: translateY(-580px); }
}

form{
    height: 720px;
    width: 440px;
    background-color: rgba(255,255,255,0.13);
    position: absolute;
    transform: translate(-50%,-50%);
    top: 50%;
    left: 50%;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.1);
    box-shadow: 0 0 40px rgba(8,7,16,0.6);
    padding: 50px 35px;
}
form *{
    font-family: 'Poppins',sans-serif;
    color: #ffffff;
    letter-spacing: 0.5px;
    outline: none;
    border: none;
}
form h3{
    font-size: 32px;
    font-weight: 500;
    line-height: 42px;
    text-align: center;
}

label{
    display: block;
    margin-top: 30px;
    font-size: 16px;
    font-weight: 500;
}
input{
    display: block;
    height: 50px;
    width: 100%;
    background-color: rgba(255,255,255,0.07);
    border-radius: 3px;
    padding: 0 10px;
    margin-top: 8px;
    font-size: 14px;
    font-weight: 300;
}
::placeholder{
    color: #e5e5e5;
}
button{
    margin-top: 50px;
    width: 100%;
    background-color: #ffffff;
    color: #080710;
    padding: 15px 0;
    font-size: 18px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
}
 

    </style>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="regiterUser.php" method="post">
        <h3>Register</h3>

        <label for="username">Customer Name</label>
        <input type="text" placeholder="Customer Name" id="customer_name" name="customer_name">

        <label for="password">Mobile Number</label>
        <!-- <input type="text" maxlength="10" placeholder="Mobile" id="customer_phone" name="customer_phone"> -->
        <input type="tel" maxlength="10" placeholder="Mobile" id="customer_phone" name="customer_phone" pattern="[0-9]{10}" inputmode="numeric" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">

        <label for="password">Customer Email</label>
        <input type="email" placeholder="Customer Email" id="customer_email" name="customer_email">
       
        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="customer_password" name="customer_password">

        <button>Register</button>
        <div style="padding-top: 20px;text-align: center;">
            Already have an Account ? <a href="login.php">Login</a>
        </div>
    </form>
</body>
</html>
