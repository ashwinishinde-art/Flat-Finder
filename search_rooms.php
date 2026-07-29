<?php
session_start();
include("../includes/db.php");

/* ======================================
   CUSTOMER LOGIN CHECK
====================================== */

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="customer")
{
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ======================================
   CUSTOMER DETAILS
====================================== */

$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->bind_param("i",$user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* ======================================
   SEARCH FILTERS
====================================== */

$location = "";
$rent = "";

/* ======================================
   GET SEARCH VALUES
====================================== */

if(isset($_GET['search']))
{
    $location = trim($_GET['location']);
    $rent = trim($_GET['rent']);
}

/* ======================================
   ROOM QUERY
====================================== */

$sql="

SELECT

rooms.*,

users.fullname,

categories.category_name,

locations.city,

locations.area

FROM rooms

INNER JOIN users
ON rooms.owner_id = users.id

LEFT JOIN categories
ON rooms.category_id = categories.id

LEFT JOIN locations
ON rooms.location_id = locations.id

/* Show ALL rooms (Available + Booked) */
WHERE 1=1

";

$params = [];
$types = "";

/* ======================================
   LOCATION FILTER
====================================== */

if($location!="")
{

$sql.="

AND
(
locations.city LIKE ?
OR
locations.area LIKE ?
)

";

$search="%".$location."%";

$params[]=$search;
$params[]=$search;

$types.="ss";

}

/* ======================================
   RENT FILTER
====================================== */

if($rent!="")
{

$sql.="
AND rooms.rent<=?
";

$params[]=$rent;

$types.="i";

}

/* ======================================
   ORDER
====================================== */

$sql.="

ORDER BY rooms.id DESC

";

$stmt = $conn->prepare($sql);

if(count($params)>0)
{
    $stmt->bind_param($types,...$params);
}

$stmt->execute();

$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
Search Rooms | Flat Finder
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

/* ================= SIDEBAR ================= */

.sidebar{
position:fixed;
left:0;
top:0;
bottom:0;
width:250px;
background:#0F4C81;
padding:25px;
overflow-y:auto;
}

.logo{
font-size:22px;
font-weight:bold;
color:#fff;
line-height:45px;
margin-bottom:40px;
}

.logo span{
color:#FFD700;
}

.sidebar a{
display:block;
padding:14px 18px;
margin-bottom:10px;
border-radius:8px;
color:#fff;
text-decoration:none;
font-weight:600;
transition:.3s;
}

.sidebar a:hover,
.sidebar .active{
background:#1565C0;
}

/* ================= MAIN ================= */

.main{
margin-left:250px;
padding:35px;
}

.page-title{
font-size:38px;
font-weight:bold;
color:#0F4C81;
margin-bottom:25px;
}

/* ================= SEARCH BOX ================= */

.search-box{
background:#fff;
padding:25px;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
margin-bottom:35px;
}

.search-form{
display:grid;
grid-template-columns:2fr 1fr 1fr;
gap:15px;
}

.search-form input,
.search-form select{
padding:14px;
border:1px solid #ccc;
border-radius:8px;
font-size:15px;
outline:none;
}

.search-form input:focus,
.search-form select:focus{
border-color:#0F4C81;
}

.search-form button{
background:#0F4C81;
color:#fff;
border:none;
border-radius:8px;
font-size:16px;
font-weight:bold;
cursor:pointer;
transition:.3s;
}

.search-form button:hover{
background:#1565C0;
}

/* ================= ROOM GRID ================= */

.rooms{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(330px,1fr));
gap:30px;
}

/* ================= RESPONSIVE ================= */

@media(max-width:900px){

.search-form{
grid-template-columns:1fr;
}

.sidebar{
position:relative;
width:100%;
height:auto;
}

.main{
margin-left:0;
}

}

.room-card{

background:#fff;

border-radius:15px;

overflow:hidden;

box-shadow:0 8px 20px rgba(0,0,0,.10);

transition:.3s;

}

.room-card:hover{

transform:translateY(-8px);

}

.room-card img{

width:100%;

height:230px;

object-fit:cover;

}

.room-content{

padding:20px;

}

.room-content h2{

color:#0F4C81;

margin-bottom:15px;

}

.room-content p{

margin:8px 0;

font-size:15px;

color:#444;

}

.price{

font-size:30px;

font-weight:bold;

color:#198754;

margin:20px 0;

}

.status{

display:inline-block;

padding:8px 18px;

border-radius:25px;

font-size:13px;

font-weight:bold;

color:#fff;

margin-bottom:18px;

}

.available{

background:#198754;

}

.booked{

background:#dc3545;

}

.btn-group{

display:flex;

gap:10px;

margin-top:15px;

}

.details-btn{

flex:1;

text-align:center;

background:#0F4C81;

color:#fff;

padding:12px;

border-radius:8px;

text-decoration:none;

font-weight:bold;

transition:.3s;

}

.details-btn:hover{

background:#1565C0;

}

.map-btn{

flex:1;

text-align:center;

background:#198754;

color:#fff;

padding:12px;

border-radius:8px;

text-decoration:none;

font-weight:bold;

transition:.3s;

}

.map-btn:hover{

background:#157347;

}

.no-room{

grid-column:1/-1;

background:#fff;

padding:60px;

border-radius:15px;

text-align:center;

font-size:20px;

color:#666;

}
</style>

</head>

<body>

<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

<div class="logo">
🏠 Flat <span>Finder</span>
</div>

<a href="dashboard.php">
🏠 Dashboard
</a>

<a href="search_rooms.php" class="active">
🔍 Search Rooms
</a>

<a href="my_bookings.php">
📑 My Bookings
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

<h1 class="page-title">
Search Rooms
</h1>

<!-- ================= SEARCH BOX ================= -->

<div class="search-box">

<form method="GET" class="search-form">

<input
type="text"
name="location"
placeholder="Enter City or Area"
value="<?php echo htmlspecialchars($location); ?>">

<select name="rent">

<option value="">Select Rent</option>

<option value="6000">₹6,000</option>
<option value="7000">₹7,000</option>
<option value="8000">₹8,000</option>
<option value="9000">₹9,000</option>
<option value="10000">₹10,000</option>
<option value="12000">₹12,000</option>
<option value="15000">₹15,000</option>
<option value="18000">₹18,000</option>
<option value="20000">₹20,000</option>
<option value="25000">₹25,000</option>
<option value="30000">₹30,000</option>

</select>

<button
type="submit"
name="search">
Search
</button>

</form>

</div>

<!-- ================= ROOM LIST START ================= -->

<div class="rooms">
   <?php

if($result->num_rows>0)
{

while($row=$result->fetch_assoc())
{

$image="../uploads/".$row['image'];

if(empty($row['image']) || !file_exists($image))
{
    $image="../images/no-image.png";
}

?>

<div class="room-card">

<img src="<?php echo $image; ?>" alt="Room Image">

<div class="room-content">

<h2>

<?php echo htmlspecialchars($row['room_title']); ?>

</h2>

<p>

📍 <strong>Location :</strong>

<?php

echo htmlspecialchars($row['area']);

echo ", ";

echo htmlspecialchars($row['city']);

?>

</p>

<p>

📂 <strong>Category :</strong>

<?php echo htmlspecialchars($row['category_name']); ?>

</p>

<p>

👤 <strong>Owner :</strong>

<?php echo htmlspecialchars($row['fullname']); ?>

</p>

<p>

📝 <strong>Description :</strong>

<?php echo htmlspecialchars(substr($row['description'],0,80)); ?>...

</p>

<div class="price">

₹<?php echo number_format($row['rent']); ?>/Month

</div>

<?php

if($row['status']=="Available")
{

?>

<div class="status available">

🟢 Available

</div>

<?php

}
else
{

?>

<div class="status booked">

🔴 Booked

</div>

<?php

}

?>

<div class="btn-group">

<a
href="room_details.php?id=<?php echo $row['id']; ?>"
class="details-btn">

View Details

</a>

<a
href="https://www.google.com/maps/search/<?php echo urlencode($row['area'].', '.$row['city']); ?>"
target="_blank"
class="map-btn">

View Map

</a>

</div>

</div>

</div>

<?php

}

}
else
{

?>

<div class="no-room">

<h2>

No Rooms Found

</h2>

<p>

No owner has added any room yet.

</p>

</div>

<?php

}

?>
