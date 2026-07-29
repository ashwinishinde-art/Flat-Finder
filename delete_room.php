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
   CHECK ROOM ID
====================================== */

if(!isset($_GET['id']))
{
    header("Location: my_rooms.php");
    exit();
}

$room_id = intval($_GET['id']);

/* ======================================
   FETCH ROOM
====================================== */

$sql="

SELECT *

FROM rooms

WHERE id=?

AND owner_id=?

";

$stmt=$conn->prepare($sql);

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

$room=$result->fetch_assoc();
/* ======================================
   DELETE ROOM IMAGE
====================================== */

if(!empty($room['image']))
{

    $imagePath = "../uploads/".$room['image'];

    if(file_exists($imagePath))
    {
        unlink($imagePath);
    }

}

/* ======================================
   DELETE ROOM FROM DATABASE
====================================== */

$delete = $conn->prepare("

DELETE FROM rooms

WHERE id=?

AND owner_id=?

");

$delete->bind_param("ii",$room_id,$owner_id);

if($delete->execute())
{

    echo "<script>

    alert('Room Deleted Successfully.');

    window.location='my_rooms.php';

    </script>";

}
else
{

    echo "<script>

    alert('Unable to delete room.');

    window.location='my_rooms.php';

    </script>";

}

/* ======================================
   CLOSE CONNECTION
====================================== */

$delete->close();
$stmt->close();
$conn->close();

?>