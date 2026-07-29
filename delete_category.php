<?php
session_start();
include("../includes/db.php");

// =========================
// Admin Login Check
// =========================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin")
{
    header("Location: ../login.php");
    exit();
}

// =========================
// Check Category ID
// =========================
if (!isset($_GET['id']))
{
    header("Location: categories.php");
    exit();
}

$id = intval($_GET['id']);

// =========================
// Check if category exists
// =========================
$check = $conn->prepare("SELECT * FROM categories WHERE id=?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 0)
{
    echo "<script>
            alert('Category not found.');
            window.location='categories.php';
          </script>";
    exit();
}

// =========================
// Check if category is used
// =========================
$roomCheck = $conn->prepare("SELECT id FROM rooms WHERE category_id=?");
$roomCheck->bind_param("i", $id);
$roomCheck->execute();
$roomResult = $roomCheck->get_result();

if ($roomResult->num_rows > 0)
{
    echo "<script>
            alert('Cannot delete! This category is used by one or more rooms.');
            window.location='categories.php';
          </script>";
    exit();
}

// =========================
// Delete Category
// =========================
$delete = $conn->prepare("DELETE FROM categories WHERE id=?");
$delete->bind_param("i", $id);

if ($delete->execute())
{
    echo "<script>
            alert('Category deleted successfully.');
            window.location='categories.php';
          </script>";
}
else
{
    echo "<script>
            alert('Failed to delete category.');
            window.location='categories.php';
          </script>";
}

$check->close();
$roomCheck->close();
$delete->close();
$conn->close();
?>