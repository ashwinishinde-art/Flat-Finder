<?php
session_start();
include("../includes/db.php");

// Check Admin Login
if(!isset($_SESSION['role']) || $_SESSION['role']!="admin")
{
    header("Location: ../login.php");
    exit();
}

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);

    $sql = "SELECT * FROM categories
            WHERE category_name LIKE '%$search%'
            ORDER BY id DESC";
}
else
{
    $sql = "SELECT * FROM categories
            ORDER BY id DESC";
}

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Manage Categories</title>

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

/* Top Header */

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

.search-box{
    margin-bottom:20px;
}

.search-box input{
    width:300px;
    padding:10px;
}

.search-box button{
    padding:10px 18px;
    background:#0b5394;
    color:white;
    border:none;
    cursor:pointer;
}

.add{
    float:right;
    background:green;
    color:white;
    text-decoration:none;
    padding:10px 20px;
}

.add:hover{
    background:#008000;
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

.edit{
    background:green;
    color:white;
    text-decoration:none;
    padding:6px 12px;
}

.delete{
    background:red;
    color:white;
    text-decoration:none;
    padding:6px 12px;
}

.edit:hover{
    background:#006400;
}

.delete:hover{
    background:#cc0000;
}

.back{
    display:inline-block;
    margin-top:20px;
    background:#222;
    color:white;
    text-decoration:none;
    padding:10px 20px;
}

.back:hover{
    background:#000;
}

</style>

</head>

<body>

<!-- Top Bar -->

<div class="top-bar">

<h2>Manage Categories</h2>

<a href="dashboard.php" class="dashboard-btn">
🏠 Dashboard
</a>

</div>

<div class="container">

<form method="GET" class="search-box">

<input
type="text"
name="search"
placeholder="Search Category"
value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">
Search
</button>

<a href="add_category.php" class="add">
+ Add Category
</a>

</form>

<table>

<tr>

<th>ID</th>
<th>Category Name</th>
<th>Action</th>

</tr>

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo htmlspecialchars($row['category_name']); ?></td>

<td>

<a
class="edit"
href="edit_category.php?id=<?php echo $row['id']; ?>">
Edit
</a>

<a
class="delete"
href="delete_category.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this category?')">
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

<td colspan="3">
No Categories Found
</td>

</tr>

<?php

}

?>

</table>

<a class="back" href="dashboard.php">
← Back to Dashboard
</a>

</div>

</body>
</html>