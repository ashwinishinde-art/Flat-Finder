<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="owner")
{
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

$sql = "SELECT
bookings.id,
bookings.booking_date,
bookings.status,
rooms.room_title,
users.fullname

FROM bookings

INNER JOIN rooms
ON bookings.room_id = rooms.id

INNER JOIN users
ON bookings.customer_id = users.id

WHERE rooms.owner_id='$owner_id'

ORDER BY bookings.id DESC";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<title>Owner Bookings</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
}

.container{
width:95%;
margin:auto;
margin-top:30px;
}

h2{
background:#111;
color:#fff;
padding:15px;
text-align:center;
}

table{
width:100%;
border-collapse:collapse;
background:#fff;
margin-top:20px;
}

table th{
background:#0d6efd;
color:white;
padding:12px;
}

table td{
padding:10px;
border:1px solid #ccc;
text-align:center;
}

.back{
display:inline-block;
margin-top:20px;
padding:10px 20px;
background:#333;
color:white;
text-decoration:none;
}

</style>

</head>

<body>

<div class="container">

<h2>Customer Bookings</h2>

<table>

<tr>
<th>ID</th>
<th>Room</th>
<th>Customer</th>
<th>Booking Date</th>
<th>Status</th>
</tr>

<?php

if(mysqli_num_rows($result)>0)
{
while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['room_title']; ?></td>

<td><?php echo $row['fullname']; ?></td>

<td><?php echo $row['booking_date']; ?></td>

<td><?php echo $row['status']; ?></td>

</tr>

<?php
}
}
else
{
?>

<tr>
<td colspan="5">No Bookings Found</td>
</tr>

<?php
}
?>

</table>

<a href="dashboard.php" class="back">Back</a>

</div>

</body>

</html>