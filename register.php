<?php
session_start();

// Prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register | Smart Flat Finder</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:url("images/bg.jpg") no-repeat center center fixed;
    background-size:cover;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px 15px;
    position:relative;
}

body::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,.45);
}

.register-box{
    position:relative;
    z-index:1;
    width:560px;
    background:#fff;
    border-radius:18px;
    padding:40px;
    box-shadow:0 15px 40px rgba(0,0,0,.30);
}

h2{
    text-align:center;
    color:#0F4C81;
    font-size:36px;
    margin-bottom:8px;
}

.subtitle{
    text-align:center;
    color:#666;
    margin-bottom:30px;
}

.input-group{
    margin-bottom:18px;
}

.input-group label{
    display:block;
    font-weight:600;
    margin-bottom:8px;
    color:#222;
}

.input-group input,
.input-group select{

    width:100%;
    padding:13px 15px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
    transition:.3s;
    background:#fff;

}

.input-group input:focus,
.input-group select:focus{

    outline:none;
    border-color:#1565C0;
    box-shadow:0 0 8px rgba(21,101,192,.25);

}

.input-group small{

    display:block;
    margin-top:6px;
    color:#666;
    font-size:13px;
    line-height:22px;

}

.error{

    color:red;
    font-size:13px;
    margin-top:6px;
    font-weight:600;

}

.btn{

    width:100%;
    padding:14px;
    background:#0F4C81;
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:17px;
    cursor:pointer;
    font-weight:bold;
    transition:.3s;

}

.btn:hover{

    background:#1565C0;

}

.bottom{

    text-align:center;
    margin-top:25px;

}

.bottom a{

    color:#1565C0;
    text-decoration:none;
    font-weight:bold;

}

.bottom a:hover{

    text-decoration:underline;

}

@media(max-width:650px){

.register-box{

width:100%;
padding:25px;

}

h2{

font-size:30px;

}

}

</style>

</head>

<body>

<div class="register-box">

<h2>Create Account</h2>

<p class="subtitle">

Please fill all the required details.

</p>

<form action="register_process.php" method="POST" autocomplete="off">
    <!-- ================= User Type ================= -->

<div class="input-group">

<label>User Type</label>

<select name="role" required>

<option value="" selected disabled>Select User Type</option>

<option value="customer">Customer</option>

<option value="owner">Property Owner</option>

</select>

</div>

<!-- ================= Full Name ================= -->

<div class="input-group">

<label>Full Name</label>

<input
type="text"
name="fullname"
placeholder="Enter Full Name"
required
maxlength="50"
pattern="[A-Za-z ]+"
autocomplete="off">

<small>

Only letters and spaces are allowed.

</small>

</div>

<!-- ================= Email ================= -->

<div class="input-group">

<label>Email Address</label>

<input
type="email"
name="email"
placeholder="example@gmail.com"
required
pattern="[a-z0-9._%+-]+@gmail\.com"
autocomplete="off">

<small>

Only lowercase Gmail addresses are accepted.

</small>

</div>

<!-- ================= Phone Number ================= -->

<div class="input-group">

<label>Phone Number</label>

<input
type="tel"
name="phone"
placeholder="Enter Your Number"
required
maxlength="10"
pattern="[0-9]{10}"
autocomplete="off">

<small>

Enter exactly 10 digits.

</small>

</div>
<!-- ================= Password ================= -->

<div class="input-group">

<label>Password</label>

<input
type="password"
name="password"
id="password"
placeholder="Enter Password"
required
autocomplete="new-password">

<!-- Live Validation Error -->
<small id="passwordError" class="error"></small>

<!-- PHP Validation Error -->
<?php
if(isset($_SESSION['password_error']))
{
?>
<p class="error">
<?php
echo $_SESSION['password_error'];
unset($_SESSION['password_error']);
?>
</p>
<?php
}
?>

<small>

✔ Minimum 8 Characters <br>

✔ One Uppercase Letter (A-Z) <br>

✔ One Lowercase Letter (a-z) <br>

✔ One Number (0-9) <br>

✔ One Special Character (@,#,$,%,&,!)

</small>

</div>

<!-- ================= Confirm Password ================= -->

<div class="input-group">

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
placeholder="Confirm Password"
required
autocomplete="new-password">

</div>

<!-- ================= Register Button ================= -->

<button
type="submit"
class="btn"
name="register">

Create Account

</button>

</form>

<!-- ================= Bottom ================= -->

<div class="bottom">

Already have an account?

<a href="login.php">

Login Here

</a>

</div>

</div>
<script>

const password = document.getElementById("password");
const passwordError = document.getElementById("passwordError");

password.addEventListener("keyup", function(){

    let pass = password.value;

    // Clear message if empty
    if(pass.length == 0)
    {
        passwordError.innerHTML = "";
        password.style.borderColor = "#ccc";
        return;
    }

    // Minimum Length
    if(pass.length < 8)
    {
        passwordError.innerHTML = "Password must be at least 8 characters.";
        password.style.borderColor = "red";
        return;
    }

    // Uppercase
    if(!/[A-Z]/.test(pass))
    {
        passwordError.innerHTML = "Password must contain at least one uppercase letter.";
        password.style.borderColor = "red";
        return;
    }

    // Lowercase
    if(!/[a-z]/.test(pass))
    {
        passwordError.innerHTML = "Password must contain at least one lowercase letter.";
        password.style.borderColor = "red";
        return;
    }

    // Number
    if(!/[0-9]/.test(pass))
    {
        passwordError.innerHTML = "Password must contain at least one number.";
        password.style.borderColor = "red";
        return;
    }

    // Special Character
    if(!/[\W_]/.test(pass))
    {
        passwordError.innerHTML = "Password must contain at least one special character.";
        password.style.borderColor = "red";
        return;
    }

    // Success
    passwordError.innerHTML = "✔ Strong Password";
    passwordError.style.color = "green";
    password.style.borderColor = "green";

});

</script>

</body>
</html>