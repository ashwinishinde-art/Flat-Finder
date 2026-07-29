<?php
session_start();
include("../includes/db.php");

// Check Admin Login
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

// Total Owners
$owners = 0;
$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='owner'");
if($query){
    $owners = mysqli_fetch_assoc($query)['total'];
}
// Total Customers
$customers = 0;
$query = mysqli_query($conn,"SELECT COUNT(*) AS total FROM users WHERE role='customer'");
if($query){
    $customers = mysqli_fetch_assoc($query)['total'];
}
// Total Rooms
$rooms = 0;
$query = mysqli_query($conn,"SELECT COUNT(*) AS total FROM rooms");
if($query){
    $rooms = mysqli_fetch_assoc($query)['total'];
}

$categories = 0;
$query = mysqli_query($conn,"SELECT COUNT(*) AS total FROM categories");
if($query){
    $categories = mysqli_fetch_assoc($query)['total'];
}

$locations = 0;
$query = mysqli_query($conn,"SELECT COUNT(*) AS total FROM locations");
if($query){
    $locations = mysqli_fetch_assoc($query)['total'];
}

// Total Bookings
$bookings = 0;

$check = mysqli_query($conn,"SHOW TABLES LIKE 'bookings'");

if($check && mysqli_num_rows($check) > 0)
{
    $query = mysqli_query($conn,"SELECT COUNT(*) AS total FROM bookings");

    if($query)
    {
        $bookings = mysqli_fetch_assoc($query)['total'];
    }
}

// Available Rooms
$available = 0;
$query = mysqli_query($conn,"SELECT COUNT(*) AS total FROM rooms WHERE status='Available'");
if($query){
    $available = mysqli_fetch_assoc($query)['total'];
}


$booked = 0;
$query = mysqli_query($conn,"SELECT COUNT(*) AS total FROM rooms WHERE status='Booked'");
if($query){
    $booked = mysqli_fetch_assoc($query)['total'];
}

// Room Report
$sql = "SELECT
rooms.room_title,
users.fullname,
categories.category_name,
locations.city,
locations.area,
rooms.rent,
rooms.status

FROM rooms
LEFT JOIN users
ON rooms.owner_id = users.id

LEFT JOIN categories
ON rooms.category_id = categories.id

LEFT JOIN locations
ON rooms.location_id = locations.id
ORDER BY rooms.id DESC";

$result = mysqli_query($conn,$sql);

if(!$result){
    die("SQL Error : ".mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Reports</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}
.report-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.print-btn{
    background:#0F4C81;
    color:white;
    padding:10px 18px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-weight:bold;
}

.print-btn:hover{
    background:#1565C0;
}

@media print{

.back,
.dashboard-btn,
.print-btn{
    display:none;
}

body{
    background:white;
}

.container{
    width:100%;
    margin:0;
}

}
body{
background:#f4f6f9;
}

.top-bar{
background:#0F4C81;
padding:18px 30px;
display:flex;
justify-content:space-between;
align-items:center;
}

.top-bar h2{
color:white;
font-size:30px;
}

.dashboard-btn{
background:white;
color:#0F4C81;
padding:10px 20px;
text-decoration:none;
font-weight:bold;
border-radius:6px;
transition:.3s;
}

.dashboard-btn:hover{
background:#FFD700;
color:black;
}

.container{
width:95%;
margin:30px auto;
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:30px;
}

.card{
background:white;
padding:25px;
border-radius:8px;
text-align:center;
box-shadow:0 2px 10px rgba(0,0,0,.15);
transition:.3s;
}

.card:hover{
transform:translateY(-5px);
}

.card h3{
font-size:34px;
color:#0F4C81;
margin-bottom:10px;
}

.card p{
font-size:16px;
font-weight:bold;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 2px 10px rgba(0,0,0,.1);
border-radius:8px;
overflow:hidden;
}

table th{
background:#0F4C81;
color:white;
padding:12px;
}

table td{
padding:12px;
border:1px solid #ddd;
text-align:center;
}

table tr:hover{
    background:#eef6ff;
}

.back{
display:inline-block;
margin-top:20px;
padding:10px 20px;
background:#222;
color:white;
text-decoration:none;
border-radius:5px;
}

.back:hover{
background:#000;
}

</style>

</head>

<body>

<div class="top-bar">

<h2>Reports</h2>

<a href="dashboard.php" class="dashboard-btn">
🏠 Dashboard
</a>

</div>

<div class="container">

<div class="cards">

<div class="card">
<h3><?php echo $owners; ?></h3>
<p>Total Owners</p>
</div>

<div class="card">
<h3><?php echo $customers; ?></h3>
<p>Total Customers</p>
</div>

<div class="card">
<h3><?php echo $rooms; ?></h3>
<p>Total Rooms</p>
</div>

<div class="card">
<h3><?php echo $categories; ?></h3>
<p>Total Categories</p>
</div>

<div class="card">
<h3><?php echo $locations; ?></h3>
<p>Total Locations</p>
</div>

<div class="card">
<h3><?php echo $bookings; ?></h3>
<p>Total Bookings</p>
</div>

<div class="card">
<h3><?php echo $available; ?></h3>
<p>Available Rooms</p>
</div>

<div class="card">
<h3><?php echo $booked; ?></h3>
<p>Booked Rooms</p>
</div>

</div>
<div class="report-header">

<div>
<h3 style="color:#0F4C81;">Room Report</h3>

<p style="margin-top:8px;font-weight:bold;">
Report Date :
<?php echo date("d-m-Y"); ?>
</p>
</div>

<button onclick="window.print()" class="print-btn">
🖨 Print Report
</button>

</div>
<table>

<tr>
<th>Room</th>
<th>Owner</th>
<th>Category</th>
<th>Location</th>
<th>Rent</th>
<th>Status</th>
</tr>

<?php

if($result && mysqli_num_rows($result)>0)
{
while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo htmlspecialchars($row['room_title'] ?? 'N/A'); ?></td>

<td><?php echo htmlspecialchars($row['fullname'] ?? 'N/A'); ?></td>

<td><?php echo htmlspecialchars($row['category_name'] ?? 'N/A'); ?></td>

<td>
<?php
echo htmlspecialchars($row['city'] ?? 'N/A');
echo " - ";
echo htmlspecialchars($row['area'] ?? 'N/A');
?>
</td>

<td>₹<?php echo number_format($row['rent'],2); ?></td>

<td>
<?php
if(strtolower(trim($row['status'])) == "available")
{
    echo "<span style='color:green;font-weight:bold;'>Available</span>";
}
else
{
    echo "<span style='color:red;font-weight:bold;'>Booked</span>";
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
<td colspan="6">No Room Records Found.</td>
</tr>

<?php
}
?>

</table>

<a href="dashboard.php" class="back">
← Back to Dashboard
</a>

</div>

</body>
</html>