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

$current_password = $_POST['current_password'];

$new_password = $_POST['new_password'];

$confirm_password = $_POST['confirm_password'];

/* ======================================
   VALIDATION
====================================== */

if(
empty($current_password) ||
empty($new_password) ||
empty($confirm_password)
)
{
    echo "<script>

    alert('Please fill all password fields.');

    window.location='profile.php';

    </script>";

    exit();
}

if($new_password != $confirm_password)
{
    echo "<script>

    alert('New Password and Confirm Password do not match.');

    window.location='profile.php';

    </script>";

    exit();
}

/* ======================================
   FETCH CURRENT PASSWORD
====================================== */

$stmt = $conn->prepare("

SELECT password

FROM users

WHERE id=?

LIMIT 1

");

$stmt->bind_param("i",$owner_id);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();
/* ======================================
   VERIFY CURRENT PASSWORD
====================================== */

if(!password_verify($current_password, $user['password']))
{
    echo "<script>

    alert('Current Password is Incorrect.');

    window.location='profile.php';

    </script>";

    exit();
}

/* ======================================
   CHECK IF NEW PASSWORD IS SAME
====================================== */

if(password_verify($new_password, $user['password']))
{
    echo "<script>

    alert('New Password cannot be the same as Current Password.');

    window.location='profile.php';

    </script>";

    exit();
}

/* ======================================
   PASSWORD STRENGTH
====================================== */

if(
strlen($new_password) < 8 ||
!preg_match('/[A-Z]/',$new_password) ||
!preg_match('/[a-z]/',$new_password) ||
!preg_match('/[0-9]/',$new_password) ||
!preg_match('/[\W_]/',$new_password)
)
{
    echo "<script>

    alert('Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number and one special character.');

    window.location='profile.php';

    </script>";

    exit();
}

/* ======================================
   HASH NEW PASSWORD
====================================== */

$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

/* ======================================
   UPDATE PASSWORD
====================================== */

$update = $conn->prepare("

UPDATE users

SET password=?

WHERE id=?

");

$update->bind_param("si",$new_hash,$owner_id);

if($update->execute())
{

    echo "<script>

    alert('Password Changed Successfully.');

    window.location='profile.php';

    </script>";

}
else
{

    echo "<script>

    alert('Unable to Change Password.');

    window.location='profile.php';

    </script>";

}

/* ======================================
   CLOSE CONNECTION
====================================== */

$stmt->close();
$update->close();
$conn->close();

?>