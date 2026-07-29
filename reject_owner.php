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
// Already Rejected
// ==========================

if($owner['status'] == "Rejected")
{
    echo "<script>
    alert('This owner is already rejected.');
    window.location='owners.php';
    </script>";
    exit();
}

// ==========================
// Reject Owner
// ==========================

$update = $conn->prepare("
UPDATE users
SET status='Rejected'
WHERE id=?
");

$update->bind_param("i", $owner_id);

if($update->execute())
{
    echo "<script>
    alert('Owner rejected successfully.');
    window.location='owners.php';
    </script>";
}
else
{
    echo "<script>
    alert('Failed to reject owner.');
    window.location='owners.php';
    </script>";
}

$update->close();
$stmt->close();
$conn->close();
?>