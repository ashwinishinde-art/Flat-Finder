<?php
session_start();
include("../includes/db.php");

// Check Admin Login
if(!isset($_SESSION['role']) || $_SESSION['role']!="admin")
{
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['id']))
{
    $id = intval($_GET['id']);

    mysqli_query($conn,"DELETE FROM locations WHERE id='$id'");

    echo "<script>
    alert('Location Deleted Successfully');
    window.location='locations.php';
    </script>";
}
else
{
    header("Location: locations.php");
}
?>