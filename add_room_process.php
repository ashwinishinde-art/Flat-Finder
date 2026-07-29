<?php
session_start();
include("../includes/db.php");

/* ===============================
   OWNER LOGIN CHECK
=============================== */

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "owner")
{
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

/* ===============================
   CHECK FORM SUBMIT
=============================== */

if($_SERVER['REQUEST_METHOD'] != "POST")
{
    header("Location: add_room.php");
    exit();
}

/* ===============================
   GET FORM DATA
=============================== */

$room_title = trim($_POST['room_title']);
$category_id = intval($_POST['category_id']);
$location_id = intval($_POST['location_id']);
$rent = intval($_POST['rent']);
$description = trim($_POST['description']);
$status = trim($_POST['status']);

/* ===============================
   DEFAULT ROOM TYPE
=============================== */

/*
Your database still has the room_type column,
but you removed it from the form.

So we insert a default value.
*/

$room_type = "Room";

/* ===============================
   VALIDATION
=============================== */

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
    window.location='add_room.php';
    </script>";
    exit();
}

/* ===============================
   IMAGE VARIABLES
=============================== */

$image_name = "";
/* ===============================
   IMAGE UPLOAD
=============================== */

if(isset($_FILES['image']) && $_FILES['image']['error']==0)
{

    $allowed = array("jpg","jpeg","png","webp");

    $file_name = $_FILES['image']['name'];

    $tmp_name = $_FILES['image']['tmp_name'];

    $file_size = $_FILES['image']['size'];

    $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    /* CHECK FILE TYPE */

    if(!in_array($extension,$allowed))
    {
        echo "<script>

        alert('Only JPG, JPEG, PNG and WEBP images are allowed.');

        window.location='add_room.php';

        </script>";

        exit();
    }

    /* CHECK FILE SIZE (MAX 5 MB) */

    if($file_size > 5 * 1024 * 1024)
    {
        echo "<script>

        alert('Image size should not exceed 5 MB.');

        window.location='add_room.php';

        </script>";

        exit();
    }

    /* GENERATE UNIQUE IMAGE NAME */

    $image_name = time()."_".rand(1000,9999).".".$extension;

    $destination = "../uploads/".$image_name;

    if(!move_uploaded_file($tmp_name,$destination))
    {
        echo "<script>

        alert('Failed to upload image.');

        window.location='add_room.php';

        </script>";

        exit();
    }

}
else
{

    echo "<script>

    alert('Please select a room image.');

    window.location='add_room.php';

    </script>";

    exit();

}
/* ===============================
   INSERT ROOM INTO DATABASE
=============================== */

$sql = "

INSERT INTO rooms(

owner_id,
category_id,
location_id,
room_title,
room_type,
rent,
description,
image,
status

)

VALUES(

?,
?,
?,
?,
?,
?,
?,
?,
?

)

";

$stmt = $conn->prepare($sql);

if(!$stmt)
{
    die("Prepare Failed : ".$conn->error);
}

$stmt->bind_param(

"iiissdsss",

$owner_id,
$category_id,
$location_id,
$room_title,
$room_type,
$rent,
$description,
$image_name,
$status

);
/* ======================================
   RENT VALIDATION
====================================== */

if($rent < 6000 || $rent > 30000)
{
    echo "<script>

    alert('Rent must be between ₹6,000 and ₹30,000.');

    window.location='add_room.php';

    </script>";

    exit();
}

if($stmt->execute())
{

    echo "<script>

    alert('Room Added Successfully.');

    window.location='my_rooms.php';

    </script>";

}
else
{

    echo "<script>

    alert('Database Error : ".$stmt->error."');

    window.location='add_room.php';

    </script>";

}
/* ===============================
   CLOSE CONNECTION
=============================== */

$stmt->close();

$conn->close();

?>