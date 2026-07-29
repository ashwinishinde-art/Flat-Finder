<?php
session_start();
include("../includes/db.php");

/* =====================================
   OWNER LOGIN CHECK
===================================== */

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "owner")
{
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

/* =====================================
   OWNER DETAILS
===================================== */

$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->bind_param("i",$owner_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

/* =====================================
   FETCH BOOKING REQUESTS
===================================== */

$sql = "

SELECT

bookings.id,
bookings.booking_date,
bookings.status,

rooms.id AS room_id,
rooms.room_title,
rooms.room_type,
rooms.image,
rooms.rent,

users.fullname,
users.email,
users.phone

FROM bookings

INNER JOIN rooms
ON bookings.room_id = rooms.id

INNER JOIN users
ON bookings.customer_id = users.id

WHERE rooms.owner_id = ?

ORDER BY bookings.booking_date DESC

";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i",$owner_id);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Booking Requests  Flat Finder

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
color:#fff;
text-decoration:none;
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
font-weight:700;
color:#0F4C81;
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
height:65px;
object-fit:cover;
border-radius:8px;
}

/* ================= STATUS ================= */

.status-pending{
background:#ffc107;
color:#000;
padding:6px 12px;
border-radius:20px;
font-size:13px;
font-weight:600;
}

.status-approved{
background:#28a745;
color:#fff;
padding:6px 12px;
border-radius:20px;
font-size:13px;
font-weight:600;
}

.status-rejected{
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
padding:8px 15px;
border-radius:6px;
text-decoration:none;
color:#fff;
font-size:14px;
font-weight:600;
margin:2px;
}

.approve{
background:#28a745;
}

.approve:hover{
background:#218838;
}

.reject{
background:#dc3545;
}

.reject:hover{
background:#c82333;
}

.view{
background:#1565C0;
}

.view:hover{
background:#0F4C81;
}

/* ================= FOOTER ================= */

.footer{
margin-top:30px;
background:#0F4C81;
color:#fff;
text-align:center;
padding:20px;
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

        <a href="my_rooms.php">
            🏡 My Rooms
        </a>

        <a href="booking_requests.php" class="active">
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

                📑 Booking Requests

            </h2>

            <div class="table-box">

                <table>

                    <tr>

                        <th>ID</th>

                        <th>Room</th>

                        <th>Image</th>

                        <th>Customer</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Rent</th>

                        <th>Date</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                    <?php

                    if($result->num_rows > 0)
                    {

                        while($booking = $result->fetch_assoc())
                        {

                            $image = "../uploads/".$booking['image'];

                            if(empty($booking['image']) || !file_exists($image))
                            {
                                $image = "../images/no-image.png";
                            }

                    ?>

                    <tr>

                        <td>

                            <?php echo $booking['id']; ?>

                        </td>

                        <td>

                            <?php echo htmlspecialchars($booking['room_title']); ?>

                        </td>

                        <td>

                            <img
                            src="<?php echo $image; ?>"
                            class="room-img">

                        </td>

                        <td>

                            <?php echo htmlspecialchars($booking['fullname']); ?>

                        </td>

                        <td>

                            <?php echo htmlspecialchars($booking['email']); ?>

                        </td>

                        <td>

                            <?php echo htmlspecialchars($booking['phone']); ?>

                        </td>

                        <td>

                            ₹<?php echo number_format($booking['rent']); ?>/Month

                        </td>

                        <td>

                            <?php echo date("d M Y",strtotime($booking['booking_date'])); ?>

                        </td>

                        <td>
                            <?php

if($booking['status']=="Pending")
{

?>

<span class="status-pending">

Pending

</span>

<?php

}
elseif($booking['status']=="Approved")
{

?>

<span class="status-approved">

Approved

</span>

<?php

}
else
{

?>

<span class="status-rejected">

Rejected

</span>

<?php

}

?>

</td>

<td>

<?php

if($booking['status']=="Pending")
{

?>

<a
href="approve_booking.php?id=<?php echo $booking['id']; ?>"
class="btn approve"
onclick="return confirm('Approve this booking request?');">

Approve

</a>

<a
href="reject_booking.php?id=<?php echo $booking['id']; ?>"
class="btn reject"
onclick="return confirm('Reject this booking request?');">

Reject

</a>

<?php

}
elseif($booking['status']=="Approved")
{

?>

<a
href="../customer/room_details.php?id=<?php echo $booking['room_id']; ?>"
class="btn view">

View Room

</a>

<?php

}
else
{

?>

—

<?php

}

?>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="10">

No Booking Requests Found

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
<!-- CONTENT END -->

</div>
<!-- MAIN END -->

</div>
<!-- DASHBOARD END -->

<?php

$stmt->close();

$conn->close();

?>

</body>

</html>