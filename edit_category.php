<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin")
{
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['id']))
{
    header("Location: categories.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM categories WHERE id='$id'");

if(mysqli_num_rows($result)==0)
{
    header("Location: categories.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $category = mysqli_real_escape_string($conn,$_POST['category_name']);

    mysqli_query($conn,"
        UPDATE categories
        SET category_name='$category'
        WHERE id='$id'
    ");

    echo "<script>
    alert('Category Updated Successfully');
    window.location='categories.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Category</title>

<style>

body{
    font-family:Arial;
    background:#f4f4f4;
}

.container{
    width:500px;
    margin:50px auto;
    background:white;
    padding:30px;
    box-shadow:0 0 10px rgba(0,0,0,.2);
    border-radius:8px;
}

h2{
    text-align:center;
    margin-bottom:25px;
}

label{
    font-weight:bold;
    display:block;
    margin-bottom:8px;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:20px;
    border:1px solid #ccc;
    border-radius:5px;
}

button{
    padding:12px 20px;
    background:#0F4C81;
    color:white;
    border:none;
    cursor:pointer;
    border-radius:5px;
}

button:hover{
    background:#1565C0;
}

.cancel{
    background:#444;
    color:white;
    text-decoration:none;
    padding:12px 20px;
    margin-left:10px;
    border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Category</h2>

<form method="POST">

<label>Category Name</label>

<input
type="text"
name="category_name"
value="<?php echo htmlspecialchars($row['category_name']); ?>"
required>

<button type="submit" name="update">
Update Category
</button>

<a href="categories.php" class="cancel">
Cancel
</a>

</form>

</div>

</body>
</html>