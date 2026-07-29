<?php
session_start();
include("../includes/db.php");

/* ======================================
   OWNER LOGIN CHECK
====================================== */

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="owner")
{
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

/* ======================================
   CHECK FORM SUBMIT
====================================== */

if($_SERVER['REQUEST_METHOD']!="POST")
{
    header("Location: my_rooms.php");
    exit();
}

/* ======================================
   GET FORM DATA
====================================== */

$room_id = intval($_POST['room_id']);

$room_title = trim($_POST['room_title']);

$category_id = intval($_POST['category_id']);

$location_id = intval($_POST['location_id']);

$rent = intval($_POST['rent']);

$description = trim($_POST['description']);

$status = trim($_POST['status']);

$old_image = $_POST['old_image'];

/* ======================================
   DEFAULT ROOM TYPE
====================================== */

$room_type = "Room";

/* ======================================
   VALIDATION
====================================== */

if(
empty($room_title) ||
empty($category_id) ||
empty($location_id) ||
empty($rent) ||
empty($description)
)
{
    echo "<script>

    alert('Please fill all required fields.');

    window.location='edit_room.php?id=".$room_id."';

    </script>";

    exit();
}

$image_name = $old_image;