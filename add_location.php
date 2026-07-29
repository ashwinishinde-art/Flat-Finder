<?php
session_start();
include("../includes/db.php");

// Check Admin Login
if(!isset($_SESSION['role']) || $_SESSION['role']!="admin")
{
    header("Location: ../login.php");
    exit();
}

$message="";

if(isset($_POST['add_location']))
{
    $city = mysqli_real_escape_string($conn, trim($_POST['city']));
    $area = mysqli_real_escape_string($conn, trim($_POST['area']));

    if($city!="" && $area!="")
    {
        // Check Duplicate
        $check = mysqli_query($conn,
        "SELECT * FROM locations
        WHERE city='$city' AND area='$area'");

        if(mysqli_num_rows($check)>0)
        {
            $message="Location already exists.";
        }
        else
        {
            mysqli_query($conn,
            "INSERT INTO locations(city, area)
            VALUES('$city','$area')");

            echo "<script>
            alert('Location Added Successfully');
            window.location='locations.php';
            </script>";
            exit();
        }
    }
    else
    {
        $message="Please enter City and Area.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Add Location</title>

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
}

h2{
text-align:center;
margin-bottom:20px;
}

label{
font-weight:bold;
}

input{
width:100%;
padding:12px;
margin-top:8px;
margin-bottom:20px;
}

button{
padding:12px 20px;
border:none;
cursor:pointer;
}

.save{
background:green;
color:white;
}

.cancel{
background:#444;
color:white;
text-decoration:none;
padding:12px 20px;
margin-left:10px;
}

.error{
color:red;
margin-bottom:15px;
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

<h2>Add Location</h2>

<?php
if($message!="")
{
    echo "<p class='error'>$message</p>";
}
?>

<form method="POST">

<label>City</label>

<input
type="text"
name="city"
placeholder="Enter City"
required>

<label>Area</label>

<input
type="text"
name="area"
placeholder="Enter Area"
required>

<button
class="save"
type="submit"
name="add_location">

Save Location

</button>

<a
class="cancel"
href="locations.php">

Cancel

</a>

</form>

</div>

</body>
</html>