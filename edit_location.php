<?php
session_start();
include("../includes/db.php");

// Check Admin Login
if(!isset($_SESSION['role']) || $_SESSION['role']!="admin")
{
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['id']))
{
    header("Location: locations.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM locations WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

if(!$row)
{
    header("Location: locations.php");
    exit();
}

$message="";

if(isset($_POST['update']))
{
    $city = mysqli_real_escape_string($conn,trim($_POST['city']));
    $area = mysqli_real_escape_string($conn,trim($_POST['area']));

    if($city!="" && $area!="")
    {
        mysqli_query($conn,"
        UPDATE locations
        SET
        city='$city',
        area='$area'
        WHERE id='$id'
        ");

        echo "<script>
        alert('Location Updated Successfully');
        window.location='locations.php';
        </script>";
        exit();
    }
    else
    {
        $message="Please enter both City and Area.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<title>Edit Location</title>

<style>

body{
font-family:Arial,sans-serif;
background:#f4f4f4;
}

.container{
width:500px;
margin:60px auto;
background:#fff;
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
background:#0d6efd;
color:#fff;
border:none;
cursor:pointer;
border-radius:5px;
}

button:hover{
background:#0b5ed7;
}

.cancel{
padding:12px 20px;
background:#444;
color:white;
text-decoration:none;
margin-left:10px;
border-radius:5px;
}

.error{
color:red;
margin-bottom:15px;
font-weight:bold;
}
.top-bar{
    background:#0F4C81;
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.top-bar h2{
    margin:0;
    font-size:26px;
}

.top-links a{
    text-decoration:none;
    color:white;
    background:#1565C0;
    padding:10px 18px;
    margin-left:10px;
    border-radius:6px;
    font-weight:bold;
    transition:.3s;
}

.top-links a:hover{
    background:#FFD700;
    color:#000;
}
</style>

</head>

<body>

<div class="container">

<h2>Edit Location</h2>

<?php
if($message!="")
{
    echo "<div class='error'>$message</div>";
}
?>

<form method="POST">

<label>City</label>

<input
type="text"
name="city"
value="<?php echo htmlspecialchars($row['city']); ?>"
required>

<label>Area</label>

<input
type="text"
name="area"
value="<?php echo htmlspecialchars($row['area']); ?>"
required>

<button
type="submit"
name="update">

Update Location

</button>

<a href="locations.php" class="cancel">

Cancel

</a>

</form>

</div>

</body>

</html>