<?php
include("../includes/db.php");

$message = "";

if(isset($_POST['add_category']))
{
    $category = trim($_POST['category_name']);

    if($category != "")
    {
        $check = mysqli_query($conn,"SELECT * FROM categories WHERE category_name='$category'");

        if(mysqli_num_rows($check)>0)
        {
            $message = "Category already exists!";
        }
        else
        {
            mysqli_query($conn,"INSERT INTO categories(category_name) VALUES('$category')");
            header("Location: categories.php");
            exit();
        }
    }
    else
    {
        $message = "Category name is required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>

<div class="container">

<h2>Add Category</h2>

<?php
if($message!="")
{
    echo "<p style='color:red;'>$message</p>";
}
?>

<form method="POST">

<label>Category Name</label>

<input
type="text"
name="category_name"
placeholder="Enter Category Name"
required>

<br><br>

<button type="submit" name="add_category">
Save Category
</button>

<a href="categories.php">
<button type="button">
Cancel
</button>
</a>

</form>

</div>

</body>
</html>