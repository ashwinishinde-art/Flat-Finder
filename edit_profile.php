<?php
session_start();
include("../includes/db.php");

// Owner Login Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "owner")
{
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

// Fetch Owner Data
$result = mysqli_query($conn,"SELECT * FROM users WHERE id='$owner_id'");

if(mysqli_num_rows($result)==0)
{
    die("Owner not found.");
}

$owner = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Profile</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#f4f6f9;
}

header{
background:#0F4C81;
color:white;
padding:18px;
display:flex;
justify-content:space-between;
align-items:center;
}

header a{
background:white;
color:#0F4C81;
text-decoration:none;
padding:10px 18px;
border-radius:5px;
font-weight:bold;
}

.container{
width:650px;
margin:40px auto;
}

.form-box{
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 2px 10px rgba(0,0,0,.2);
}

.form-box h2{
text-align:center;
margin-bottom:25px;
color:#0F4C81;
}

label{
display:block;
margin-top:15px;
font-weight:bold;
}

input{
width:100%;
padding:12px;
margin-top:5px;
border:1px solid #ccc;
border-radius:5px;
font-size:15px;
}

button{
margin-top:25px;
width:100%;
padding:14px;
background:#0F4C81;
color:white;
border:none;
font-size:17px;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#1565C0;
}

</style>

</head>

<body>

<header>

<h2>Edit Profile</h2>

<a href="profile.php">Back</a>

</header>

<div class="container">

<div class="form-box">

<h2>Update Owner Details</h2>

<form action="update_profile.php" method="POST">

<label>Full Name</label>

<input
type="text"
name="fullname"
value="<?php echo htmlspecialchars($owner['fullname']); ?>"
required>

<label>Email</label>

<input
type="email"
name="email"
value="<?php echo htmlspecialchars($owner['email']); ?>"
required>

<label>Phone</label>

<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($owner['phone']); ?>"
required>

<label>New Password (Leave blank if you don't want to change it)</label>

<input
type="password"
name="password">

<button type="submit" name="update">

Update Profile

</button>

</form>

</div>

</div>

</body>

</html>