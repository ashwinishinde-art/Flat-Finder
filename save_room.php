<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/db.php");

// ===============================
// Owner Login Check
// ===============================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "owner")
{
    header("Location: ../login.php");
    exit();
}

// ===============================
// Check Form Submit
// ===============================
if (!isset($_POST['save']))
{
    header("Location: add_room.php");
    exit();
}

$owner_id = (int)$_SESSION['user_id'];

$room_title  = trim($_POST['room_title']);
$category_id = (int)$_POST['category_id'];
$location_id = (int)$_POST['location_id'];
$rent        = trim($_POST['rent']);
$description = trim($_POST['description']);

// ===============================
// Validation
// ===============================
if (
    empty($room_title) ||
    $category_id == 0 ||
    $location_id == 0 ||
    empty($rent) ||
    empty($description)
)
{
    die("Please fill all fields.");
}

// ===============================
// Check Category Exists
// ===============================
$checkCat = mysqli_query($conn, "SELECT id FROM categories WHERE id=$category_id");

if (!$checkCat || mysqli_num_rows($checkCat) == 0)
{
    die("Invalid Category.");
}

// ===============================
// Check Location Exists
// ===============================
$checkLoc = mysqli_query($conn, "SELECT id FROM locations WHERE id=$location_id");

if (!$checkLoc || mysqli_num_rows($checkLoc) == 0)
{
    die("Invalid Location.");
}

// ===============================
// Image Upload
// ===============================
$image = "";

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0)
{
    $allowed = array("jpg","jpeg","png","gif","webp");

    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowed))
    {
        die("Only JPG, JPEG, PNG, GIF and WEBP images are allowed.");
    }

    if (!is_dir("../uploads"))
    {
        mkdir("../uploads", 0777, true);
    }

    $image = time() . "_" . basename($_FILES['image']['name']);

    $target = "../uploads/" . $image;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target))
    {
        die("Failed to upload image.");
    }
}

// ===============================
// Insert Room
// ===============================
$sql = "INSERT INTO rooms
(
owner_id,
category_id,
location_id,
room_title,
rent,
description,
image,
status
)
VALUES
(
?,?,?,?,?,?,?,?
)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt)
{
    die("Prepare Failed: " . mysqli_error($conn));
}

$status = "Available";

mysqli_stmt_bind_param(
    $stmt,
    "iiisdsss",
    $owner_id,
    $category_id,
    $location_id,
    $room_title,
    $rent,
    $description,
    $image,
    $status
);

if (mysqli_stmt_execute($stmt))
{
    echo "<script>
    alert('Room Added Successfully');
    window.location='my_rooms.php';
    </script>";
}
else
{
    die("Execute Failed: " . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>