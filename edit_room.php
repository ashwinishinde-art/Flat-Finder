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
   FETCH ROOM
====================================== */

$sql="

SELECT *

FROM rooms

WHERE id=?

AND owner_id=?

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

/* ======================================
   FETCH CATEGORIES
====================================== */

$categories=$conn->query("
SELECT *
FROM categories
ORDER BY category_name ASC
");

/* ======================================
   FETCH LOCATIONS
====================================== */

$locations=$conn->query("
SELECT *
FROM locations
ORDER BY city ASC, area ASC
");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Edit Room  Flat Finder

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

.page-title{
font-size:30px;
font-weight:700;
color:#0F4C81;
margin-bottom:25px;
}

/* ================= FORM ================= */

.form-box{
background:#fff;
padding:35px;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
}

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

input,
select,
textarea{
width:100%;
padding:12px;
border:1px solid #ccc;
border-radius:8px;
font-size:15px;
outline:none;
transition:.3s;
}

input:focus,
select:focus,
textarea:focus{
border-color:#1565C0;
box-shadow:0 0 8px rgba(21,101,192,.20);
}

textarea{
resize:vertical;
min-height:120px;
}

.current-image{
width:220px;
height:170px;
object-fit:cover;
border-radius:10px;
border:2px solid #ddd;
margin-bottom:15px;
display:block;
}

input[type=file]{
padding:8px;
background:#f8f8f8;
}

/* ================= BUTTON ================= */

.save-btn{
margin-top:20px;
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

.current-image{
width:100%;
height:250px;
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

✏ Edit Room

</h2>

<div class="form-box">

<form
action="update_room.php"
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="room_id"
value="<?php echo $room['id']; ?>">

<input
type="hidden"
name="old_image"
value="<?php echo $room['image']; ?>">

<div class="form-grid">

<!-- ================= ROOM TITLE ================= -->

<div>

<label>

Room Title

</label>

<input
type="text"
name="room_title"
required
value="<?php echo htmlspecialchars($room['room_title']); ?>">

</div>

<!-- ================= CATEGORY ================= -->

<div>

<label>

Category

</label>

<select
name="category_id"
required>

<?php

while($cat=$categories->fetch_assoc())
{

?>

<option
value="<?php echo $cat['id']; ?>"
<?php if($cat['id']==$room['category_id']) echo "selected"; ?>>

<?php echo htmlspecialchars($cat['category_name']); ?>

</option>

<?php

}

?>

</select>

</div>

<!-- ================= LOCATION ================= -->

<div>

<label>

Location

</label>

<select
name="location_id"
required>

<?php

while($loc=$locations->fetch_assoc())
{

?>

<option
value="<?php echo $loc['id']; ?>"
<?php if($loc['id']==$room['location_id']) echo "selected"; ?>>

<?php

echo htmlspecialchars($loc['area']).", ".
htmlspecialchars($loc['city']);

?>

</option>

<?php

}

?>

</select>

</div>

<!-- ================= RENT ================= -->

<div>

<label>

Monthly Rent (₹)

</label>

<input
type="number"
name="rent"
required
value="<?php echo $room['rent']; ?>">

</div>
<!-- ================= CURRENT IMAGE ================= -->

<div class="full-width">

<label>

Current Room Image

</label>

<?php

$image="../uploads/".$room['image'];

if(empty($room['image']) || !file_exists($image))
{
    $image="../images/no-image.png";
}

?>

<img
src="<?php echo $image; ?>"
class="current-image"
alt="Room Image">

<label>

Upload New Image (Optional)

</label>

<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.webp">

</div>

<!-- ================= DESCRIPTION ================= -->

<div class="full-width">

<label>

Room Description

</label>

<textarea
name="description"
required><?php echo htmlspecialchars($room['description']); ?></textarea>

</div>

<!-- ================= STATUS ================= -->

<div>

<label>

Room Status

</label>

<select
name="status"
required>

<option
value="Available"
<?php if($room['status']=="Available") echo "selected"; ?>>

Available

</option>

<option
value="Booked"
<?php if($room['status']=="Booked") echo "selected"; ?>>

Booked

</option>

</select>

</div>

<!-- ================= SAVE BUTTON ================= -->

<div class="full-width">

<button
type="submit"
class="save-btn">

💾 Save Changes

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