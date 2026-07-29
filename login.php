<?php
session_start();

// Prevent browser cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title> Flat Finder | Login</title>

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

.login-box{

position:relative;
z-index:1;
width:430px;
background:#fff;
padding:40px;
border-radius:18px;
box-shadow:0 15px 40px rgba(0,0,0,.30);

}

.logo{

text-align:center;
margin-bottom:25px;

}

.logo h1{

font-size:36px;
color:#0F4C81;
font-weight:bold;

}

.logo p{

color:#666;
margin-top:8px;

}

label{

display:block;
margin-top:18px;
margin-bottom:8px;
font-weight:600;
color:#333;

}

input,
select{

width:100%;
padding:13px;
border:1px solid #ccc;
border-radius:8px;
font-size:15px;
transition:.3s;

}

input:focus,
select:focus{

outline:none;
border-color:#1565C0;
box-shadow:0 0 8px rgba(21,101,192,.25);

}

button{

width:100%;
padding:14px;
margin-top:25px;
background:#0F4C81;
color:#fff;
border:none;
border-radius:8px;
font-size:17px;
font-weight:bold;
cursor:pointer;
transition:.3s;

}

button:hover{

background:#1565C0;

}

.success{

background:#d4edda;
color:#155724;
padding:12px;
border-radius:8px;
margin-bottom:15px;
text-align:center;
font-weight:bold;

}

.error{

background:#f8d7da;
color:#721c24;
padding:12px;
border-radius:8px;
margin-bottom:15px;
text-align:center;
font-weight:bold;

}

.links{

margin-top:25px;
text-align:center;

}

.links p{

margin:8px 0;

}

.links a{

text-decoration:none;
color:#1565C0;
font-weight:bold;

}

.links a:hover{

text-decoration:underline;

}

@media(max-width:500px){

.login-box{

width:95%;
padding:30px;

}

.logo h1{

font-size:30px;

}

}

</style>

</head>
<body>

<div class="login-box">

<div class="logo">

<h1>Flat Finder</h1>

<p>Login to Continue</p>

</div>

<!-- ================= SUCCESS MESSAGE ================= -->

<?php

if(isset($_SESSION['success']))
{
?>

<div class="success">

<?php

echo $_SESSION['success'];

unset($_SESSION['success']);

?>

</div>

<?php
}
?>

<!-- ================= LOGIN SUCCESS ================= -->

<?php

if(isset($_SESSION['login_success']))
{
?>

<div class="success">

<?php

echo $_SESSION['login_success'];

unset($_SESSION['login_success']);

?>

</div>

<?php
}
?>

<!-- ================= ERROR MESSAGE ================= -->

<?php

if(isset($_SESSION['error']))
{
?>

<div class="error">

<?php

echo $_SESSION['error'];

unset($_SESSION['error']);

?>

</div>

<?php
}
?>

<form
action="login_process.php"
method="POST"
id="loginForm"
autocomplete="off">

<label>Select User Type</label>

<select
name="role"
required>

<option value="">-- Select User Type --</option>

<option value="customer">Customer</option>

<option value="owner">Property Owner</option>

<option value="admin">Admin</option>

</select>

<label>Email Address</label>

<input
type="email"
name="email"
placeholder="Enter Email Address"
required
autocomplete="off">

<label>Password</label>

<input
type="password"
name="password"
id="password"
placeholder="Enter Password"
required
autocomplete="new-password">

<button
type="submit"
name="login">

Login

</button>

</form>

<div class="links">

<p>

Don't have an account?

<a href="register.php">

Register Here

</a>

</p>

<p>

<a href="index.php">

← Back to Home

</a>

</p>

</div>

</div>
<script>

// ================================
// Show / Hide Password
// ================================

const password = document.getElementById("password");

// Create Eye Button
const eye = document.createElement("span");

eye.innerHTML = "👁";
eye.style.position = "absolute";
eye.style.right = "15px";
eye.style.marginTop = "-38px";
eye.style.cursor = "pointer";
eye.style.fontSize = "18px";

password.parentNode.appendChild(eye);

eye.onclick = function()
{
    if(password.type=="password")
    {
        password.type="text";
        eye.innerHTML="🙈";
    }
    else
    {
        password.type="password";
        eye.innerHTML="👁";
    }
};

// ================================
// Clear Form on Refresh
// ================================

window.onload=function()
{
    document.getElementById("loginForm").reset();

    if(window.history.replaceState)
    {
        window.history.replaceState(null,null,window.location.href);
    }
};

// ================================
// Prevent Browser Back Cache
// ================================

window.onpageshow=function(event)
{
    if(event.persisted)
    {
        document.getElementById("loginForm").reset();
    }
};

</script>

</body>
</html>