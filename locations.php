<?php
session_start();
include("../includes/db.php");

// Check Admin Login
if(!isset($_SESSION['role']) || $_SESSION['role']!="admin")
{
    header("Location: ../login.php");
    exit();
}

$search="";

if(isset($_GET['search']))
{
    $search=mysqli_real_escape_string($conn,$_GET['search']);

    $sql="SELECT * FROM locations
          WHERE city LIKE '%$search%'
          OR area LIKE '%$search%'
          ORDER BY id DESC";
}
else
{
    $sql="SELECT * FROM locations
          ORDER BY id DESC";
}

$result=mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Manage Locations</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#f4f4f4;
}

/* Top Bar */

.top-bar{
background:#0F4C81;
color:white;
padding:18px 30px;
display:flex;
justify-content:space-between;
align-items:center;
}

.top-bar h2{
font-size:30px;
}

.dashboard-btn{
background:white;
color:#0F4C81;
text-decoration:none;
padding:10px 20px;
border-radius:6px;
font-weight:bold;
}

.dashboard-btn:hover{
background:#FFD700;
color:black;
}

/* Container */

.container{
width:95%;
margin:30px auto;
}

/* Search */

.top{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.search-box input{
padding:10px;
width:300px;
border:1px solid #ccc;
}

.search-box button{
padding:10px 18px;
background:#0b5394;
color:white;
border:none;
cursor:pointer;
}

.btn-add{
background:green;
color:white;
text-decoration:none;
padding:10px 20px;
border-radius:4px;
}

.btn-add:hover{
background:#006400;
}

/* Table */

table{
width:100%;
border-collapse:collapse;
background:white;
}

table th{
background:#0b5394;
color:white;
padding:12px;
}

table td{
border:1px solid #ddd;
padding:12px;
text-align:center;
}

/* Buttons */

.edit-btn{
background:green;
color:white;
text-decoration:none;
padding:6px 14px;
border-radius:4px;
}

.delete-btn{
background:red;
color:white;
text-decoration:none;
padding:6px 14px;
border-radius:4px;
margin-left:5px;
}

.edit-btn:hover{
background:#006400;
}

.delete-btn:hover{
background:#cc0000;
}

.back-btn{
display:inline-block;
margin-top:20px;
background:#222;
color:white;
text-decoration:none;
padding:10px 20px;
}

.back-btn:hover{
background:black;
}

</style>

</head>

<body>

<div class="top-bar">

<h2>Manage Locations</h2>

<a href="dashboard.php" class="dashboard-btn">
🏠 Dashboard
</a>

</div>

<div class="container">

<div class="top">

<form method="GET" class="search-box">

<input
type="text"
name="search"
placeholder="Search City or Area"
value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">
Search
</button>

</form>

<a href="add_location.php" class="btn-add">
+ Add Location
</a>

</div>

<table>

<tr>

<th>Sr.No</th>
<th>City</th>
<th>Area</th>
<th>Action</th>

</tr>

<?php

$sr=1;

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $sr++; ?></td>

<td><?php echo htmlspecialchars($row['city']); ?></td>

<td><?php echo htmlspecialchars($row['area']); ?></td>

<td>

<a
class="edit-btn"
href="edit_location.php?id=<?php echo $row['id']; ?>">
Edit
</a>

<a
class="delete-btn"
href="delete_location.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this location?')">
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

<td colspan="4">

No Locations Found

</td>

</tr>

<?php

}

?>

</table>

<a href="dashboard.php" class="back-btn">

← Back to Dashboard

</a>

</div>

</body>
</html>