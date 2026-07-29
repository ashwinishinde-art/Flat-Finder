<?php
session_start();
include("../includes/db.php");

/* ==========================
   CUSTOMER LOGIN CHECK
========================== */

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "customer") {
    header("Location: ../login.php");
    exit();
}

/* ==========================
   CHECK ROOM ID
========================== */

if (!isset($_GET['id'])) {
    header("Location: rooms.php");
    exit();
}

$room_id = intval($_GET['id']);

/* ==========================
   FETCH ROOM DETAILS
========================== */

$sql = "
SELECT
    rooms.*,
    locations.city,
    locations.area,
    users.fullname,
    users.email,
    users.phone
FROM rooms

INNER JOIN users
ON rooms.owner_id = users.id

LEFT JOIN locations
ON rooms.location_id = locations.id

WHERE rooms.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>
    alert('Room not found.');
    window.location='rooms.php';
    </script>";
    exit();
}

$room = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Smart Flat Finder | Room Details</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#f4f6f9;
}

.header{
background:#0F4C81;
color:#fff;
padding:20px 40px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 5px 15px rgba(0,0,0,.15);
}

.header h2{
font-size:28px;
}

.back{
background:#198754;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:8px;
font-weight:600;
transition:.3s;
}

.back:hover{
background:#157347;
}

.container{
width:90%;
max-width:1100px;
margin:35px auto;
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 10px 25px rgba(0,0,0,.12);
}
.room-image{
width:100%;
height:450px;
object-fit:cover;
border-radius:12px;
margin-bottom:30px;
box-shadow:0 5px 15px rgba(0,0,0,.15);
}

.title{
font-size:34px;
color:#0F4C81;
margin-bottom:20px;
font-weight:700;
}

.info{
font-size:17px;
margin-bottom:15px;
color:#444;
line-height:28px;
}

.info strong{
color:#0F4C81;
}

.price{
font-size:32px;
font-weight:bold;
color:#198754;
margin:25px 0;
}

.description{
margin-top:25px;
padding:20px;
background:#f8f9fa;
border-left:5px solid #0F4C81;
border-radius:8px;
line-height:28px;
color:#555;
}

.owner-box{
margin-top:35px;
padding:20px;
background:#eef5ff;
border-radius:10px;
}

.owner-box h3{
margin-bottom:15px;
color:#0F4C81;
}

.book-btn{
display:inline-block;
margin-top:30px;
padding:14px 35px;
background:#198754;
color:#fff;
text-decoration:none;
border-radius:8px;
font-size:18px;
font-weight:600;
transition:.3s;
}

.book-btn:hover{
background:#157347;
transform:translateY(-2px);
}

.booked{
display:inline-block;
margin-top:30px;
padding:14px 35px;
background:#dc3545;
color:#fff;
border-radius:8px;
font-size:18px;
font-weight:600;
}

footer{
margin-top:40px;
background:#0F4C81;
color:white;
text-align:center;
padding:18px;
font-size:15px;
}

@media(max-width:768px){

.header{
flex-direction:column;
gap:15px;
text-align:center;
}

.container{
width:95%;
padding:20px;
}

.room-image{
height:250px;
}

.title{
font-size:28px;
}

.price{
font-size:26px;
}

.book-btn,
.booked{
display:block;
text-align:center;
width:100%;
}
}

</style>

</head>
<body>

<div class="header">

    <h2>🏠 Smart Flat Finder</h2>

    <a href="search_rooms.php" class="back">
        ← Back to Rooms
    </a>

</div>

<div class="container">

<?php

$image = "../uploads/" . $room['image'];

if(empty($room['image']) || !file_exists($image))
{
    $image = "../images/no-image.png";
}

?>

<img src="<?php echo $image; ?>" class="room-image" alt="Room Image">

<h1 class="title">
    <?php echo htmlspecialchars($room['room_title']); ?>
</h1>

<div class="price">
    ₹<?php echo number_format($room['rent']); ?> / Month
</div>

<p class="info">
    <strong>🏠 Room Type :</strong>
    <?php echo htmlspecialchars($room['room_type']); ?>
</p>

<p class="info">
    <strong>📍 Location :</strong>
    <?php echo htmlspecialchars($room['city']); ?>,
    <?php echo htmlspecialchars($room['area']); ?>
</p>

<p class="info">
    <strong>📌 Status :</strong>
    <?php echo htmlspecialchars($room['status']); ?>
</p>

<div class="description">

<h3 style="color:#0F4C81;margin-bottom:15px;">
Description
</h3>

<?php echo nl2br(htmlspecialchars($room['description'])); ?>

</div>

<div class="owner-box">

<h3>Owner Information</h3>

<p class="info">
<strong>👤 Name :</strong>
<?php echo htmlspecialchars($room['fullname']); ?>
</p>

<p class="info">
<strong>📧 Email :</strong>
<?php echo htmlspecialchars($room['email']); ?>
</p>

<p class="info">
<strong>📞 Phone :</strong>
<?php echo htmlspecialchars($room['phone']); ?>
</p>
<?php if($room['status']=="Available"){ ?>

<a href="book_room.php?id=<?php echo $room['id']; ?>"
class="book-btn"
onclick="return confirm('Are you sure you want to book this room?');">

🏠 Book Now

</a>

<?php } else { ?>

<span class="booked">

❌ This Room is Already Booked

</span>

<?php } ?>

</div>

<footer>

© <?php echo date("Y"); ?> Smart Flat Finder | All Rights Reserved.

</footer>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>