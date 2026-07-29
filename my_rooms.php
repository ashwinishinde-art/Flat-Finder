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
   FETCH OWNER ROOMS
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

WHERE rooms.owner_id=?

ORDER BY rooms.id DESC

";

$stmt=$conn->prepare($sql);

$stmt->bind_param("i",$owner_id);

$stmt->execute();

$result=$stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

My Rooms  Flat Finder

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
    color:#0F4C81;
    font-weight:700;
    margin-bottom:25px;
}

/* ================= TABLE ================= */

.table-box{
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#0F4C81;
    color:#fff;
    padding:14px;
    text-align:center;
}

table td{
    padding:14px;
    text-align:center;
    border-bottom:1px solid #ddd;
    vertical-align:middle;
}

.room-img{
    width:90px;
    height:70px;
    object-fit:cover;
    border-radius:8px;
}

/* ================= STATUS ================= */

.available{
    background:#28a745;
    color:#fff;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.booked{
    background:#dc3545;
    color:#fff;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

/* ================= BUTTONS ================= */

.btn{
    display:inline-block;
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    margin:2px;
    color:#fff;
}

.view{
    background:#1565C0;
}

.view:hover{
    background:#0F4C81;
}

.edit{
    background:#FFC107;
    color:#000;
}

.edit:hover{
    background:#E0A800;
}

.delete{
    background:#DC3545;
}

.delete:hover{
    background:#C82333;
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

🏠  <span>Flat</span> Finder

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

<h2 class="page-title">

🏡 My Rooms

</h2>

<div class="table-box">

<table>

<tr>

<th>ID</th>

<th>Image</th>

<th>Room Title</th>

<th>Category</th>

<th>Location</th>

<th>Rent</th>

<th>Status</th>

<th>Action</th>

</tr>

<?php

if($result->num_rows > 0)
{

while($room = $result->fetch_assoc())
{

$image="../uploads/".$room['image'];

if(empty($room['image']) || !file_exists($image))
{
    $image="../images/no-image.png";
}

?>

<tr>

<td>

<?php echo $room['id']; ?>

</td>

<td>

<img
src="<?php echo $image; ?>"
class="room-img"
alt="Room">

</td>

<td>

<?php echo htmlspecialchars($room['room_title']); ?>

</td>

<td>

<?php echo htmlspecialchars($room['category_name']); ?>

</td>

<td>

<?php

echo htmlspecialchars($room['area']).", ".
htmlspecialchars($room['city']);

?>

</td>

<td>

₹<?php echo number_format($room['rent']); ?>/Month

</td>

<td>
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

</td>

<td>

<a
href="owner_room_details.php?id=<?php echo $room['id']; ?>"
class="btn view">

View

</a>

<a
href="edit_room.php?id=<?php echo $room['id']; ?>"
class="btn edit">

Edit

</a>

<a
href="delete_room.php?id=<?php echo $room['id']; ?>"
class="btn delete"
onclick="return confirm('Are you sure you want to delete this room?');">

Delete

</a>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="8">

No Rooms Added Yet

</td>

</tr>

<?php

}

?>

</table>

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