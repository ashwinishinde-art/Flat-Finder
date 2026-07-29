<?php
session_start();
include("../includes/db.php");

// ==========================
// Admin Login Check
// ==========================

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin")
{
    header("Location: ../login.php");
    exit();
}

// ==========================
// Search
// ==========================

$search = "";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);
}

$sql = "
SELECT *
FROM users
WHERE role='customer'
AND
(
    fullname LIKE ?
    OR
    email LIKE ?
    OR
    phone LIKE ?
)
ORDER BY id DESC
";

$stmt = $conn->prepare($sql);

$keyword = "%".$search."%";

$stmt->bind_param(
"sss",
$keyword,
$keyword,
$keyword
);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Manage Customers</title>

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
color:white;
padding:18px 30px;
display:flex;
justify-content:space-between;
align-items:center;
}

.logout{
background:#dc3545;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
}

.logout:hover{
background:#bb2d3b;
}

.container{
width:95%;
margin:30px auto;
}

.top{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.back{
background:#198754;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
}

.back:hover{
background:#157347;
}

.search-box{
display:flex;
gap:10px;
}

.search-box input{
width:300px;
padding:10px;
border:1px solid #ccc;
border-radius:5px;
}

.search-box button{
background:#0F4C81;
color:white;
padding:10px 18px;
border:none;
border-radius:5px;
cursor:pointer;
}

.search-box button:hover{
background:#1565C0;
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
padding:14px;
text-align:center;
border-bottom:1px solid #ddd;
}

.delete{
background:#dc3545;
color:white;
padding:8px 15px;
text-decoration:none;
border-radius:5px;
}

.delete:hover{
background:#bb2d3b;
}

.empty{
padding:40px;
text-align:center;
}

</style>

</head>

<body>

<div class="header">

<h2>Manage Customers</h2>

<a href="../logout.php" class="logout">

Logout

</a>

</div>

<div class="container">

<div class="top">

<a href="dashboard.php" class="back">

← Dashboard

</a>

<form method="GET" class="search-box">

<input
type="text"
name="search"
placeholder="Search Customer..."
value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">

Search

</button>

</form>

</div>

<table>

<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>Action</th>

</tr>
<?php

if($result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
?>

<tr>

<td>

<?php echo $row['id']; ?>

</td>

<td>

<?php echo htmlspecialchars($row['fullname']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['email']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['phone']); ?>

</td>

<td>

<a
href="delete_customer.php?id=<?php echo $row['id']; ?>"
class="delete"
onclick="return confirm('Are you sure you want to delete this customer?');">

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

<td colspan="5">

<div class="empty">

<h2>No Customers Found</h2>

<br>

<p>

There are no customer records available.

</p>

</div>

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>