<?php
session_start();
include("includes/db.php");

if(isset($_POST['login']))
{
    // ==========================
    // Get Login Details
    // ==========================

    $role = trim($_POST['role']);
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    // ==========================
    // Validate User Type
    // ==========================

    if(!in_array($role, ["admin","customer","owner"]))
    {
        $_SESSION['error'] = "Please select a valid User Type.";
        header("Location: login.php");
        exit();
    }

    // ==========================
    // Validate Email
    // ==========================

    if(empty($email))
    {
        $_SESSION['error'] = "Email Address is required.";
        header("Location: login.php");
        exit();
    }

    // ==========================
    // Validate Password
    // ==========================

    if(empty($password))
    {
        $_SESSION['error'] = "Password is required.";
        header("Location: login.php");
        exit();
    }

    // ==========================
    // Find User
    // ==========================

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role=?");
    $stmt->bind_param("ss",$email,$role);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows==0)
    {
        $_SESSION['error'] = "Email or User Type is incorrect.";
        header("Location: login.php");
        exit();
    }

    $user = $result->fetch_assoc();

    // ==========================
    // Verify Password
    // ==========================

    if(!password_verify($password,$user['password']))
    {
        $_SESSION['error'] = "Incorrect Password.";
        header("Location: login.php");
        exit();
    }

    // ==========================
    // Owner Approval Check
    // ==========================

    if($user['role']=="owner")
    {
        if($user['status']=="Pending")
        {
            $_SESSION['error'] = "Your account is waiting for Admin approval.";
            header("Location: login.php");
            exit();
        }

        if($user['status']=="Rejected")
        {
            $_SESSION['error'] = "Your account has been rejected by Admin.";
            header("Location: login.php");
            exit();
        }
    }

    // ==========================
    // Create Session
    // ==========================

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    // Login Success Message
    $_SESSION['login_success'] = "Welcome, ".$user['fullname']."! Login Successful.";

    // ==========================
    // Redirect User
    // ==========================

    if($user['role']=="admin")
    {
        header("Location: admin/dashboard.php");
        exit();
    }

    if($user['role']=="customer")
    {
        header("Location: customer/dashboard.php");
        exit();
    }

    if($user['role']=="owner")
    {
        header("Location: owner/dashboard.php");
        exit();
    }
}
else
{
    $_SESSION['error'] = "Invalid Request.";
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();
?>