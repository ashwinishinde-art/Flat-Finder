<?php
session_start();
include("../includes/db.php");

// ==========================
// Admin Login Check
// ==========================

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin")
{
    header("Location: ../login.php");
    exit();
}

// ==========================
// Check Owner ID
// ==========================

if(!isset($_GET['id']))
{
    header("Location: owners.php");
    exit();
}

$owner_id = intval($_GET['id']);

// ==========================
// Verify Owner
// ==========================

$stmt = $conn->prepare("
SELECT id, fullname, status
FROM users
WHERE id=?
AND role='owner'
");

$stmt->bind_param("i", $owner_id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0)
{
    echo "<script>
    alert('Owner not found.');
    window.location='owners.php';
    </script>";
    exit();
}

$owner = $result->fetch_assoc();

// ==========================
// Already Approved
// ==========================

if($owner['status'] == "Approved")
{
    echo "<script>
    alert('This owner is already approved.');
    window.location='owners.php';
    </script>";
    exit();
}

// ==========================
// Approve Owner
// ==========================

$update = $conn->prepare("
UPDATE users
SET status='Approved'
WHERE id=?
");

$update->bind_param("i", $owner_id);

if($update->execute())
{
    echo "<script>
    alert('Owner approved successfully.');
    window.location='owners.php';
    </script>";
}
else
{
    echo "<script>
    alert('Failed to approve owner.');
    window.location='owners.php';
    </script>";
}

$update->close();
$stmt->close();
$conn->close();

?>