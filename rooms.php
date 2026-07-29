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

$user_id=$_SESSION['user_id'];

/* ======================================
   CUSTOMER DETAILS
====================================== */

$stmt=$conn->prepare("

SELECT *

FROM users

WHERE id=?

");

$stmt->bind_param("i",$user_id);

$stmt->execute();

$user=$stmt->get_result()->fetch_assoc();

/* ======================================
   SEARCH FILTERS
====================================== */

$location="";
$rent="";

if(isset($_GET['search']))
{
    $location=trim($_GET['location']);
    $rent=trim($_GET['rent']);
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
ON rooms.owner_id=users.id

LEFT JOIN categories
ON rooms.category_id=categories.id

LEFT JOIN locations
ON rooms.location_id=locations.id

WHERE rooms.status='Available'

";

$params=[];
$types="";

/* ================= LOCATION FILTER ================= */

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

/* ================= RENT FILTER ================= */

if($rent!="")
{

$sql.="

AND rooms.rent<=?

";

$params[]=$rent;

$types.="i";

}

/* ================= ORDER ================= */

$sql.="

ORDER BY rooms.id DESC

";

$stmt=$conn->prepare($sql);

if(count($params)>0)
{
    $stmt->bind_param($types,...$params);
}

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

Smart Flat Finder | Rooms

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
background:#F4F7FC;
}

.container{
width:95%;
margin:auto;
}

/* ================= HEADER ================= */

.header{
height:75px;
background:#0F4C81;
display:flex;
justify-content:space-between;
align-items:center;
padding:0 40px;
color:#fff;
box-shadow:0 5px 15px rgba(0,0,0,.15);
}

.logo h2{
font-size:30px;
}

.navbar{
display:flex;
gap:25px;
align-items:center;
}

.navbar a{
color:#fff;
text-decoration:none;
font-weight:600;
transition:.3s;
}

.navbar a:hover,
.navbar .active{
color:#FFD700;
}

.user{
display:flex;
align-items:center;
gap:20px;
}

.logout{
background:#DC3545;
padding:10px 18px;
border-radius:8px;
text-decoration:none;
color:#fff;
font-weight:bold;
}

.logout:hover{
background:#BB2D3B;
}

/* ================= HERO ================= */

.hero{
background:linear-gradient(135deg,#0F4C81,#1565C0);
padding:40px;
margin:30px 0;
border-radius:20px;
color:#fff;
}

.hero h1{
font-size:38px;
margin-bottom:10px;
}

.hero p{
font-size:17px;
}

/* ================= SEARCH ================= */

.search-box{
display:grid;
grid-template-columns:3fr 2fr 1fr;
gap:15px;
margin-top:25px;
}

.search-box input{
padding:14px;
border:none;
border-radius:10px;
font-size:15px;
outline:none;
}

.search-box button{
background:#FFD700;
border:none;
border-radius:10px;
font-size:16px;
font-weight:bold;
cursor:pointer;
transition:.3s;
}

.search-box button:hover{
background:#fff;
}

@media(max-width:900px){

.search-box{
grid-template-columns:1fr;
}

}

</style>

</head>

<body>

<!-- ================= HEADER ================= -->

<div class="header">

<div class="logo">

<h2>🏠  Flat Finder</h2>

</div>

<div class="navbar">

<a href="dashboard.php">Dashboard</a>

<a href="rooms.php" class="active">Rooms</a>

<a href="my_bookings.php">My Bookings</a>

<a href="profile.php">Profile</a>

</div>

<div class="user">

<span>

👋 <?php echo htmlspecialchars($user['fullname']); ?>

</span>

<a href="../logout.php" class="logout">

Logout

</a>

</div>

</div>

<div class="container">

<!-- ================= HERO SECTION ================= -->

<div class="hero">

<h1>

Find Your Dream Room 🏠

</h1>

<p>

Search verified rooms without brokers.

</p>

<form method="GET" class="search-box">

<input
type="text"
name="location"
placeholder="📍 Enter City or Area"
value="<?php echo htmlspecialchars($location); ?>">

<input
type="number"
name="rent"
placeholder="💰 Maximum Rent"
value="<?php echo htmlspecialchars($rent); ?>">

<button
type="submit"
name="search">

🔍 Search

</button>

</form>

</div>
<!-- ================= ROOM CARDS ================= -->

<style>

.rooms{

display:grid;

grid-template-columns:repeat(auto-fit,minmax(330px,1fr));

gap:30px;

margin-top:40px;

}

.card{

background:#fff;

border-radius:18px;

overflow:hidden;

box-shadow:0 10px 25px rgba(0,0,0,.12);

transition:.35s;

position:relative;

}

.card:hover{

transform:translateY(-10px);

box-shadow:0 20px 45px rgba(0,0,0,.20);

}

.card img{

width:100%;

height:230px;

object-fit:cover;

transition:.4s;

}

.card:hover img{

transform:scale(1.05);

}

.ribbon{

position:absolute;

top:18px;

left:-35px;

background:#ff9800;

color:#fff;

padding:8px 45px;

font-size:13px;

font-weight:bold;

transform:rotate(-45deg);

}

.card-body{

padding:22px;

}

.top-icons{

display:flex;

justify-content:space-between;

margin-bottom:12px;

}

.badge{

background:#28a745;

color:#fff;

padding:5px 12px;

border-radius:20px;

font-size:12px;

font-weight:bold;

}

.heart{

font-size:22px;

color:red;

}

.card-body h2{

color:#0F4C81;

margin-bottom:10px;

}

.rating{

color:#ff9800;

margin-bottom:12px;

}

.status{

display:inline-block;

background:#198754;

color:#fff;

padding:6px 15px;

border-radius:20px;

font-size:13px;

margin-top:10px;

margin-bottom:15px;

}

.buttons{

display:flex;

gap:10px;

margin-top:18px;

}

.buttons a{

flex:1;

padding:12px;

text-align:center;

text-decoration:none;

border-radius:8px;

font-weight:bold;

}

.view-btn{

background:#0F4C81;

color:#fff;

}

.view-btn:hover{

background:#1565C0;

}

.map-btn{

background:#198754;

color:#fff;

}

.map-btn:hover{

background:#157347;

}

.empty{

grid-column:1/-1;

background:#fff;

padding:60px;

border-radius:15px;

text-align:center;

}

</style>

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

<div class="card">

<div class="ribbon">

FEATURED

</div>

<img src="<?php echo $image; ?>">

<div class="card-body">

<div class="top-icons">

<span class="badge">

Available

</span>

<span class="heart">

❤

</span>

</div>

<h2>

<?php echo htmlspecialchars($row['room_title']); ?>

</h2>

<div class="rating">

⭐⭐⭐⭐☆ (4.5)

</div>

<p>

📂

<strong>Category :</strong>

<?php echo htmlspecialchars($row['category_name']); ?>

</p>

<p>

📍

<strong>Location :</strong>

<?php

echo htmlspecialchars($row['area']).", ".
htmlspecialchars($row['city']);

?>

</p>

<p>

👤

<strong>Owner :</strong>

<?php echo htmlspecialchars($row['fullname']); ?>

</p>

<h3 style="color:#198754;font-size:28px;">

₹ <?php echo number_format($row['rent']); ?>

<span style="font-size:15px;color:#777;">

/ Month

</span>

</h3>

<div class="status">

<?php echo htmlspecialchars($row['status']); ?>

</div>

<div class="buttons">

<a
href="room_details.php?id=<?php echo $row['id']; ?>"
class="view-btn">

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

<div class="empty">

<h2>

No Rooms Available

</h2>

<p>

No owner has added any room yet.

</p>

</div>

<?php

}

?>

</div>
<!-- ================= RECOMMENDED ================= -->

<style>

.recommend{

margin-top:60px;

}

.recommend h2{

font-size:32px;

color:#0F4C81;

margin-bottom:10px;

}

.recommend p{

color:#666;

margin-bottom:25px;

}

.recommend-box{

display:grid;

grid-template-columns:repeat(auto-fit,minmax(250px,1fr));

gap:25px;

}

.recommend-card{

background:#fff;

padding:35px;

text-align:center;

border-radius:18px;

box-shadow:0 10px 25px rgba(0,0,0,.12);

transition:.3s;

font-size:45px;

}

.recommend-card:hover{

transform:translateY(-10px);

}

.recommend-card h3{

margin:15px 0;

color:#0F4C81;

font-size:22px;

}

.recommend-card p{

color:#666;

font-size:15px;

}

/* ================= FOOTER ================= */

footer{

margin-top:70px;

background:#0F4C81;

color:#fff;

}

.footer-container{

display:grid;

grid-template-columns:repeat(auto-fit,minmax(250px,1fr));

gap:40px;

padding:45px;

}

.footer-box h3{

margin-bottom:18px;

font-size:24px;

}

.footer-box p{

line-height:28px;

color:#ddd;

}

.footer-box ul{

list-style:none;

}

.footer-box ul li{

margin:12px 0;

}

.footer-box ul li a{

color:#ddd;

text-decoration:none;

transition:.3s;

}

.footer-box ul li a:hover{

color:#FFD700;

}

.copyright{

text-align:center;

padding:18px;

background:#08345A;

font-size:15px;

}

@media(max-width:768px){

.header{

flex-direction:column;

height:auto;

padding:20px;

}

.navbar{

flex-wrap:wrap;

justify-content:center;

margin-top:15px;

}

.user{

margin-top:15px;

}

.hero{

text-align:center;

padding:30px 20px;

}

.hero h1{

font-size:30px;

}

.rooms{

grid-template-columns:1fr;

}

.footer-container{

grid-template-columns:1fr;

text-align:center;

}

}

</style>

<div class="recommend">

<h2>

⭐ Recommended For You

</h2>

<p>

Discover the best verified rooms and flats available today.

</p>

<div class="recommend-box">

<div class="recommend-card">

🏡

<h3>

Budget Friendly

</h3>

<p>

Affordable rooms for students and working professionals.

</p>

</div>

<div class="recommend-card">

📍

<h3>

Prime Locations

</h3>

<p>

Find rooms near colleges, offices and public transport.

</p>

</div>

<div class="recommend-card">

🔥

<h3>

Most Popular

</h3>

<p>

Top-rated rooms chosen by our customers.

</p>

</div>

<div class="recommend-card">

💎

<h3>

Premium Flats

</h3>

<p>

Luxury accommodation with premium facilities.

</p>

</div>

</div>

</div>

<!-- ================= FOOTER ================= -->

<footer>

<div class="footer-container">

<div class="footer-box">

<h3>

🏠  Flat Finder

</h3>

<p>

Smart Flat Finder helps students, professionals and families find verified rental rooms and flats without brokers.

</p>

</div>

<div class="footer-box">

<h3>

Quick Links

</h3>

<ul>

<li><a href="dashboard.php">Dashboard</a></li>

<li><a href="rooms.php">Rooms</a></li>

<li><a href="my_bookings.php">My Bookings</a></li>

<li><a href="profile.php">Profile</a></li>

</ul>

</div>

<div class="footer-box">

<h3>

Contact Us

</h3>

<p>

📍 Pune, Maharashtra

</p>

<p>

📞 +91 9876543210

</p>

<p>

✉ support@flatfinder.com

</p>

</div>

</div>

<div class="copyright">

© <?php echo date("Y"); ?> Smart Flat Finder | All Rights Reserved.

</div>

</footer>

</div>
<!-- END CONTAINER -->

</body>

</html>

<?php

$stmt->close();

$conn->close();

?>