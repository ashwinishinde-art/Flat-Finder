<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="admin")
{
    header("Location: ../login.php");
    exit();
}

include("../includes/db.php");

$result=mysqli_query($conn,"
SELECT *
FROM users
WHERE role='owner'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Owner Requests</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{
background:#f4f6f9;
}

.container{
width:95%;
margin:30px auto;
}

h2{
margin-bottom:20px;
color:#0F4C81;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 5px 15px rgba(0,0,0,.15);
}

table th{
background:#0F4C81;
color:white;
padding:15px;
}

table td{
padding:15px;
border-bottom:1px solid #ddd;
text-align:center;
}

.approve{

background:#28a745;
color:white;
padding:8px 15px;
text-decoration:none;
border-radius:5px;

}

.reject{

background:#dc3545;
color:white;
padding:8px 15px;
text-decoration:none;
border-radius:5px;

}

.pending{

background:orange;
color:white;
padding:5px 12px;
border-radius:20px;

}

.accepted{

background:green;
color:white;
padding:5px 12px;
border-radius:20px;

}

.rejected{

background:red;
color:white;
padding:5px 12px;
border-radius:20px;

}

.back{

display:inline-block;
margin-bottom:20px;
padding:10px 20px;
background:#1565C0;
color:white;
text-decoration:none;
border-radius:6px;

}

</style>

</head>

<body>

<div class="container">

<a href="dashboard.php" class="back">

← Back Dashboard

</a>

<h2>

Property Owner Approval Requests

</h2>

<table>

<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>Status</th>

<th>Approve</th>

<th>Reject</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['fullname']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td>

<?php

if($row['status']=="Pending")
{
echo "<span class='pending'>Pending</span>";
}

elseif($row['status']=="Approved")
{
echo "<span class='accepted'>Approved</span>";
}

else
{
echo "<span class='rejected'>Rejected</span>";
}

?>

</td>

<td>

<?php

if($row['status']=="Pending")
{

?>

<a
class="approve"
href="approve_owner.php?id=<?php echo $row['id']; ?>">

Approve

</a>

<?php

}
else
{

echo "-";

}

?>

</td>

<td>

<?php

if($row['status']=="Pending")
{

?>

<a
class="reject"
href="reject_owner.php?id=<?php echo $row['id']; ?>">

Reject

</a>

<?php

}
else
{

echo "-";

}

?>

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>