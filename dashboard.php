<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin")
{
    header("Location: ../login.php");
    exit();
}

include("../includes/db.php");

/* ===========================
   Dashboard Counts
=========================== */

// Total Users
$totalUsers = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM users")
);

// Customers
$totalCustomers = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM users WHERE role='customer'")
);

// Approved Owners
$totalOwners = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM users
    WHERE role='owner'
    AND status='Approved'")
);

// Pending Owners
$pendingOwners = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM users
    WHERE role='owner'
    AND status='Pending'")
);

// Rooms
$roomQuery = mysqli_query($conn,"SHOW TABLES LIKE 'rooms'");

if(mysqli_num_rows($roomQuery)>0)
{
    $totalRooms = mysqli_num_rows(
        mysqli_query($conn,"SELECT * FROM rooms")
    );
}
else
{
    $totalRooms = 0;
}

// Bookings
$bookingQuery = mysqli_query($conn,"SHOW TABLES LIKE 'bookings'");

if(mysqli_num_rows($bookingQuery)>0)
{
    $totalBookings = mysqli_num_rows(
        mysqli_query($conn,"SELECT * FROM bookings")
    );
}
else
{
    $totalBookings = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard | Flat Finder</title>

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

.dashboard{
display:flex;
min-height:100vh;
}

/* Sidebar */

.sidebar{
width:260px;
background:#0F4C81;
padding:25px;
}

.sidebar h2{
color:#fff;
text-align:center;
margin-bottom:35px;
}

.sidebar a{
display:block;
color:#fff;
text-decoration:none;
padding:13px 15px;
margin-bottom:10px;
border-radius:8px;
transition:.3s;
}

.sidebar a:hover{
background:#1565C0;
}

.menu{
margin-bottom:15px;
}

.menu summary{
color:#fff;
padding:13px;
cursor:pointer;
font-weight:bold;
border-radius:8px;
list-style:none;
}

.menu summary:hover{
background:#1565C0;
}

.menu a{
margin-left:18px;
padding:10px;
display:block;
}

.badge{
background:red;
color:#fff;
padding:3px 8px;
border-radius:20px;
font-size:12px;
float:right;
}

/* Main */

.main{
flex:1;
padding:35px;
}

.top-bar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
}

.top-bar h1{
color:#0F4C81;
}

.logout-btn{
background:#dc3545;
color:#fff;
padding:10px 22px;
text-decoration:none;
border-radius:8px;
font-weight:600;
transition:.3s;
}

.logout-btn:hover{
background:#c82333;
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
}

.card{
background:#fff;
padding:30px;
border-radius:12px;
text-align:center;
box-shadow:0 5px 15px rgba(0,0,0,.15);
transition:.3s;
}

.card:hover{
transform:translateY(-5px);
}

.card h2{
font-size:42px;
color:#0F4C81;
margin-bottom:10px;
}

.card p{
font-size:18px;
color:#444;
}

</style>

</head>

<body>

<div class="dashboard">
    <!-- ================= Sidebar ================= -->

<div class="sidebar">

<h2>🏠 Flat Finder</h2>

<a href="dashboard.php">🏠 Dashboard</a>

<details class="menu" open>

<summary>👥 Manage Users</summary>

<a href="customers.php">👤 Customers</a>

<a href="owners.php">🏢 Property Owners</a>

<a href="owner_requests.php">

📋 Owner Requests

<?php
if($pendingOwners>0)
{
    echo "<span class='badge'>$pendingOwners</span>";
}
?>

</a>

</details>

<a href="rooms.php">🏠 Manage Rooms</a>

<a href="categories.php">📂 Categories</a>

<a href="locations.php">📍 Locations</a>

<a href="bookings.php">📑 Bookings</a>

<a href="reports.php">📊 Reports</a>

</div>

<!-- ================= Main ================= -->

<div class="main">

<div class="top-bar">

<div>

<h1>

Welcome,

<?php echo htmlspecialchars($_SESSION['fullname']); ?>

</h1>

<p style="color:#666;font-size:15px;">

Administrator Dashboard

</p>

</div>

<a href="../logout.php" class="logout-btn">

🚪 Logout

</a>

</div>

<!-- ================= Dashboard Cards ================= -->

<div class="cards">

<div class="card">

<h2><?php echo $totalUsers; ?></h2>

<p>Total Users</p>

</div>

<div class="card">

<h2><?php echo $totalCustomers; ?></h2>

<p>Total Customers</p>

</div>

<div class="card">

<h2><?php echo $totalOwners; ?></h2>

<p>Approved Owners</p>

</div>

<div class="card">

<h2><?php echo $pendingOwners; ?></h2>

<p>Pending Owner Requests</p>

</div>

<div class="card">

<h2><?php echo $totalRooms; ?></h2>

<p>Total Rooms</p>

</div>

<div class="card">

<h2><?php echo $totalBookings; ?></h2>

<p>Total Bookings</p>

</div>

</div>

<!-- Quick Actions Removed -->
 </div>

</div>

</body>

</html>