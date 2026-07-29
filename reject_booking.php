<?php
session_start();
include("../includes/db.php");

/* =====================================
   OWNER LOGIN CHECK
===================================== */

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="owner")
{
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

/* =====================================
   CHECK BOOKING ID
===================================== */

if(!isset($_GET['id']))
{
    header("Location: booking_requests.php");
    exit();
}

$booking_id = intval($_GET['id']);

/* =====================================
   VERIFY OWNER
===================================== */

$sql = "

SELECT
bookings.id

FROM bookings

INNER JOIN rooms
ON bookings.room_id = rooms.id

WHERE bookings.id = ?
AND rooms.owner_id = ?

";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii",$booking_id,$owner_id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0)
{
    header("Location: booking_requests.php");
    exit();
}

/* =====================================
   REJECT BOOKING
===================================== */

$stmt = $conn->prepare("
UPDATE bookings
SET status='Rejected'
WHERE id=?
");

$stmt->bind_param("i",$booking_id);
$stmt->execute();

/* =====================================
   REDIRECT
===================================== */

header("Location: booking_requests.php");
exit();

?>