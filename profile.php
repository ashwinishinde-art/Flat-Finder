<?php
session_start();
include("../includes/db.php");

/* ======================================
   OWNER LOGIN CHECK
====================================== */

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="owner")
{
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

/* ======================================
   FETCH OWNER DETAILS
====================================== */

$stmt = $conn->prepare("

SELECT *

FROM users

WHERE id=?

LIMIT 1

");

$stmt->bind_param("i",$owner_id);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if(!$user)
{
    session_destroy();

    header("Location: ../login.php");

    exit();
}

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

My Profile  Flat Finder

</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#eef3f9;
}

.dashboard{
display:flex;
min-height:100vh;
}

/* ================= SIDEBAR ================= */

.sidebar{
width:260px;
background:#0F4C81;
position:fixed;
top:0;
left:0;
bottom:0;
padding:25px;
}

.logo{
font-size:30px;
font-weight:700;
color:#fff;
text-align:center;
margin-bottom:40px;
}

.logo span{
color:#FFD700;
}

.sidebar a{
display:block;
padding:15px 18px;
margin-bottom:10px;
border-radius:8px;
text-decoration:none;
color:#fff;
font-weight:500;
transition:.3s;
}

.sidebar a:hover,
.sidebar .active{
background:#1565C0;
}

/* ================= MAIN ================= */

.main{
margin-left:260px;
width:calc(100% - 260px);
}

/* ================= TOPBAR ================= */

.topbar{
height:80px;
background:#fff;
display:flex;
justify-content:space-between;
align-items:center;
padding:0 35px;
box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.topbar h1{
font-size:28px;
color:#0F4C81;
}

.user-box{
display:flex;
align-items:center;
gap:20px;
}

.logout{
background:#dc3545;
color:#fff;
text-decoration:none;
padding:10px 18px;
border-radius:8px;
font-weight:600;
}

.logout:hover{
background:#bb2d3b;
}

/* ================= CONTENT ================= */

.content{
padding:35px;
}

.page-title{
font-size:30px;
font-weight:700;
color:#0F4C81;
margin-bottom:25px;
}

/* ================= PROFILE CARD ================= */

.profile-card{
background:#fff;
padding:35px;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
margin-bottom:30px;
}

.section-title{
font-size:24px;
font-weight:700;
color:#0F4C81;
margin-bottom:25px;
border-left:5px solid #0F4C81;
padding-left:15px;
}

/* ================= FORM ================= */

.form-grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:25px;
}

.full-width{
grid-column:1/-1;
}

label{
display:block;
margin-bottom:8px;
font-weight:600;
color:#333;
}

input{
width:100%;
padding:13px;
border:1px solid #ccc;
border-radius:8px;
font-size:15px;
outline:none;
transition:.3s;
}

input:focus{
border-color:#1565C0;
box-shadow:0 0 8px rgba(21,101,192,.20);
}

/* ================= BUTTON ================= */

.save-btn{
margin-top:15px;
background:#0F4C81;
color:#fff;
border:none;
padding:14px 30px;
border-radius:8px;
font-size:17px;
font-weight:600;
cursor:pointer;
transition:.3s;
}

.save-btn:hover{
background:#1565C0;
}

/* ================= FOOTER ================= */

.footer{
margin-top:30px;
background:#0F4C81;
color:#fff;
text-align:center;
padding:20px;
}

/* ================= RESPONSIVE ================= */

@media(max-width:900px){

.form-grid{
grid-template-columns:1fr;
}

.sidebar{
width:220px;
}

.main{
margin-left:220px;
width:calc(100% - 220px);
}

}

@media(max-width:768px){

.sidebar{
position:relative;
width:100%;
height:auto;
}

.main{
margin-left:0;
width:100%;
}

.topbar{
flex-direction:column;
height:auto;
padding:20px;
gap:15px;
}

}

</style>

</head>

<body>
    <div class="dashboard">

<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

<div class="logo">

🏠 <span>Flat</span> Finder

</div>

<a href="dashboard.php">
🏠 Dashboard
</a>

<a href="add_room.php">
➕ Add Room
</a>

<a href="my_rooms.php">
🏡 My Rooms
</a>

<a href="booking_requests.php">
📑 Booking Requests
</a>

<a href="profile.php" class="active">
👤 My Profile
</a>

<a href="../logout.php">
🚪 Logout
</a>

</div>

<!-- ================= MAIN ================= -->

<div class="main">

<!-- ================= TOPBAR ================= -->

<div class="topbar">

<h1>

Welcome,
<?php echo htmlspecialchars($user['fullname']); ?>

</h1>

<div class="user-box">

<span>

🏠 Property Owner

</span>

<a href="../logout.php" class="logout">

Logout

</a>

</div>

</div>

<!-- ================= CONTENT ================= -->

<div class="content">

<h2 class="page-title">

👤 My Profile

</h2>

<!-- ================= EDIT PROFILE ================= -->

<div class="profile-card">

<h3 class="section-title">

Edit Profile

</h3>

<form
action="update_profile.php"
method="POST">

<div class="form-grid">

<!-- FULL NAME -->

<div>

<label>

Full Name

</label>

<input
type="text"
name="fullname"
required
value="<?php echo htmlspecialchars($user['fullname']); ?>">

</div>

<!-- EMAIL -->

<div>

<label>

Email Address

</label>

<input
type="email"
name="email"
required
value="<?php echo htmlspecialchars($user['email']); ?>">

</div>

<!-- MOBILE -->

<div>

<label>

Mobile Number

</label>

<input
type="text"
name="mobile"
required
value="<?php echo htmlspecialchars($user['phone']); ?>"

</div>

<!-- ROLE -->

<div>

<label>

Account Type

</label>

<input
type="text"
value="<?php echo ucfirst($user['role']); ?>"
readonly>

</div>

<!-- UPDATE BUTTON -->

<div class="full-width">

<button
type="submit"
class="save-btn">

💾 Update Profile

</button>

</div>

</div>

</form>

</div>

<!-- ================= CHANGE PASSWORD ================= -->

<div class="profile-card">

<h3 class="section-title">

🔒 Change Password

</h3>

<form
action="change_password.php"
method="POST">

<div class="form-grid">

<div>

<label>

Current Password

</label>

<input
type="password"
name="current_password"
required>

</div>

<div>

<label>

New Password

</label>

<input
type="password"
name="new_password"
required>

</div>

<div>

<label>

Confirm New Password

</label>

<input
type="password"
name="confirm_password"
required>

</div>

<div class="full-width">

<button
type="submit"
class="save-btn">

🔑 Change Password

</button>

</div>

</div>

</form>

</div>
<!-- ================= FOOTER ================= -->

<div class="footer">

<p>

© <?php echo date("Y"); ?>

 Flat Finder 

</p>

</div>

</div>
<!-- END CONTENT -->

</div>
<!-- END MAIN -->

</div>
<!-- END DASHBOARD -->

<?php

$stmt->close();

$conn->close();

?>

</body>
</html>