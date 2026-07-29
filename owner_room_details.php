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
   CHECK ROOM ID
====================================== */

if(!isset($_GET['id']))
{
    header("Location: my_rooms.php");
    exit();
}

$room_id = intval($_GET['id']);

/* ======================================
   OWNER DETAILS
====================================== */

$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->bind_param("i",$owner_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

/* ======================================
   FETCH ROOM DETAILS
====================================== */

$sql="

SELECT

rooms.*,

locations.city,
locations.area,

categories.category_name

FROM rooms

LEFT JOIN locations
ON rooms.location_id = locations.id

LEFT JOIN categories
ON rooms.category_id = categories.id

WHERE rooms.id=?

AND rooms.owner_id=?

";

$stmt=$conn->prepare($sql);

$stmt->bind_param("ii",$room_id,$owner_id);

$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0)
{
    header("Location: my_rooms.php");
    exit();
}

$room=$result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Room Details  Flat Finder

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
color:#0F4C81;
font-size:28px;
}

.user-box{
display:flex;
align-items:center;
gap:20px;
}

.logout{
background:#dc3545;
color:#fff;
padding:10px 18px;
border-radius:8px;
text-decoration:none;
font-weight:600;
}

.logout:hover{
background:#bb2d3b;
}

/* ================= CONTENT ================= */

.content{
padding:35px;
}

.room-box{
background:#fff;
padding:30px;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
}

.room-image{
width:100%;
height:450px;
object-fit:cover;
border-radius:12px;
margin-bottom:25px;
}

.room-title{
font-size:32px;
font-weight:700;
color:#0F4C81;
margin-bottom:25px;
}

.info{
margin-bottom:18px;
font-size:17px;
}

.info strong{
color:#0F4C81;
}

.available{
display:inline-block;
background:#28a745;
color:#fff;
padding:8px 16px;
border-radius:20px;
font-weight:600;
}

.booked{
display:inline-block;
background:#dc3545;
color:#fff;
padding:8px 16px;
border-radius:20px;
font-weight:600;
}

/* ================= BUTTONS ================= */

.btn{
display:inline-block;
padding:12px 20px;
border-radius:8px;
text-decoration:none;
font-weight:600;
margin-right:10px;
margin-top:25px;
color:#fff;
}

.back{
background:#6c757d;
}

.back:hover{
background:#5a6268;
}

.edit{
background:#ffc107;
color:#000;
}

.edit:hover{
background:#e0a800;
}

/* ================= FOOTER ================= */

.footer{
margin-top:30px;
background:#0F4C81;
color:#fff;
text-align:center;
padding:20px;
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

.room-image{
height:260px;
}

}

</style>

</head>

<body>
    <div class="dashboard">

<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

<div class="logo">

🏠 Smart <span>Flat</span> Finder

</div>

<a href="dashboard.php">

🏠 Dashboard

</a>

<a href="add_room.php">

➕ Add Room

</a>

<a href="my_rooms.php" class="active">

🏡 My Rooms

</a>

<a href="booking_requests.php">

📑 Booking Requests

</a>

<a href="profile.php">

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

<div class="room-box">

<?php

$image="../uploads/".$room['image'];

if(empty($room['image']) || !file_exists($image))
{
    $image="../images/no-image.png";
}

?>

<img
src="<?php echo $image; ?>"
class="room-image"
alt="Room Image">

<h2 class="room-title">

<?php echo htmlspecialchars($room['room_title']); ?>

</h2>

<p class="info">

<strong>Category :</strong>

<?php echo htmlspecialchars($room['category_name']); ?>

</p>

<p class="info">

<strong>Location :</strong>

<?php

echo htmlspecialchars($room['area']).", ".
htmlspecialchars($room['city']);

?>

</p>

<p class="info">

<strong>Monthly Rent :</strong>

₹<?php echo number_format($room['rent']); ?>/Month

</p>



<p class="info">

<strong>Description :</strong><br><br>

<?php echo nl2br(htmlspecialchars($room['description'])); ?>

</p>

<p class="info">

<strong>Status :</strong>

<?php

if($room['status']=="Available")
{

?>

<span class="available">

Available

</span>

<?php

}
else
{

?>

<span class="booked">

Booked

</span>

<?php

}

?>

</p>

<!-- ================= BUTTONS ================= -->

<a
href="my_rooms.php"
class="btn back">

← Back

</a>

<a
href="edit_room.php?id=<?php echo $room['id']; ?>"
class="btn edit">

✏ Edit Room

</a>

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