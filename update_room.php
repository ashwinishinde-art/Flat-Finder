<?php
session_start();
include("../includes/db.php");

/* ==========================
   OWNER LOGIN CHECK
========================== */

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="owner")
{
    header("Location: ../login.php");
    exit();
}

$owner_id=$_SESSION['user_id'];

/* ==========================
   FORM SUBMIT CHECK
========================== */

if($_SERVER["REQUEST_METHOD"]!="POST")
{
    header("Location: my_rooms.php");
    exit();
}

/* ==========================
   GET FORM DATA
========================== */

$room_id      = intval($_POST['room_id']);
$room_title   = trim($_POST['room_title']);
$category_id  = intval($_POST['category_id']);
$location_id  = intval($_POST['location_id']);
$rent         = trim($_POST['rent']);
$description  = trim($_POST['description']);
$status       = trim($_POST['status']);
$old_image    = $_POST['old_image'];

/* ==========================
   VALIDATION
========================== */

if(
    empty($room_title) ||
    empty($rent) ||
    empty($description)
)
{
    echo "<script>
    alert('Please fill all required fields.');
    window.history.back();
    </script>";
    exit();
}

/* ==========================
   CHECK ROOM EXISTS
========================== */

$stmt=$conn->prepare("
SELECT *
FROM rooms
WHERE id=?
AND owner_id=?
");

$stmt->bind_param("ii",$room_id,$owner_id);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0)
{
    echo "<script>
    alert('Room not found.');
    window.location='my_rooms.php';
    </script>";
    exit();
}
/* ==========================
   IMAGE UPLOAD HANDLING
========================== */

$image_name = $old_image;


if(isset($_FILES['image']) && $_FILES['image']['name']!="")
{

    $image=$_FILES['image'];

    $image_name=time()."_".basename($image['name']);

    $target="../uploads/".$image_name;


    $allowed=array(
        "jpg",
        "jpeg",
        "png",
        "webp"
    );


    $ext=strtolower(
        pathinfo($image_name,PATHINFO_EXTENSION)
    );


    if(!in_array($ext,$allowed))
    {
        echo "<script>
        alert('Only JPG, JPEG, PNG and WEBP images are allowed.');
        window.history.back();
        </script>";
        exit();
    }


    if($image['size'] > 5000000)
    {
        echo "<script>
        alert('Image size should be less than 5MB.');
        window.history.back();
        </script>";
        exit();
    }


    if(!move_uploaded_file($image['tmp_name'],$target))
    {
        echo "<script>
        alert('Image upload failed.');
        window.history.back();
        </script>";
        exit();
    }


    /* Delete old image */

    if(
        !empty($old_image) &&
        file_exists("../uploads/".$old_image)
    )
    {
        unlink("../uploads/".$old_image);
    }

}



/* ==========================
   UPDATE ROOM DATA
========================== */


$stmt=$conn->prepare("
UPDATE rooms
SET 
room_title=?,
category_id=?,
location_id=?,
rent=?,
description=?,
image=?,
status=?
WHERE id=?
AND owner_id=?
");


$stmt->bind_param(
"siissssii",
$room_title,
$category_id,
$location_id,
$rent,
$description,
$image_name,
$status,
$room_id,
$owner_id
);



if($stmt->execute())
{

    echo "<script>
    alert('Room updated successfully.');
    window.location='my_rooms.php';
    </script>";

}
else
{

    echo "<script>
    alert('Something went wrong.');
    window.history.back();
    </script>";

}


$stmt->close();
$conn->close();

?>