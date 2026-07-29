<?php
session_start();
include("../includes/db.php");

/* ======================================
   OWNER LOGIN CHECK
====================================== */

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "owner")
{
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

/* ======================================
   CHECK FORM SUBMIT
====================================== */

if($_SERVER['REQUEST_METHOD'] != "POST")
{
    header("Location: profile.php");
    exit();
}

/* ======================================
   GET FORM DATA
====================================== */

$fullname = trim($_POST['fullname']);
$email    = trim($_POST['email']);
$mobile   = trim($_POST['mobile']);

/* ======================================
   VALIDATION
====================================== */

if(empty($fullname) || empty($email) || empty($mobile))
{
    echo "<script>

    alert('Please fill all fields.');

    window.location='profile.php';

    </script>";

    exit();
}

/* ======================================
   EMAIL VALIDATION
====================================== */

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    echo "<script>

    alert('Invalid Email Address.');

    window.location='profile.php';

    </script>";

    exit();
}
/* ======================================
   CHECK DUPLICATE EMAIL
====================================== */

$check = $conn->prepare("

SELECT id

FROM users

WHERE email=?

AND id!=?

LIMIT 1

");

$check->bind_param("si",$email,$owner_id);

$check->execute();

$duplicate = $check->get_result();

if($duplicate->num_rows > 0)
{
    echo "<script>

    alert('Email Address already exists.');

    window.location='profile.php';

    </script>";

    exit();
}

/* ======================================
   MOBILE VALIDATION
====================================== */

if(!preg_match("/^[0-9]{10}$/",$mobile))
{
    echo "<script>

    alert('Enter a valid 10-digit Mobile Number.');

    window.location='profile.php';

    </script>";

    exit();
}

/* ======================================
   UPDATE PROFILE
====================================== */

$update = $conn->prepare("

UPDATE users

SET

fullname=?,
email=?,
phone=?

WHERE id=?

");

$update->bind_param(

"sssi",

$fullname,
$email,
$mobile,
$owner_id

);

if($update->execute())
{

    echo "<script>

    alert('Profile Updated Successfully.');

    window.location='profile.php';

    </script>";

}
else
{

    echo "<script>

    alert('Unable to update profile.');

    window.location='profile.php';

    </script>";

}

/* ======================================
   CLOSE CONNECTION
====================================== */

$check->close();
$update->close();
$conn->close();

?>
