<?php
session_start();
include("includes/db.php");

if(isset($_POST['register']))
{

    // ==========================
    // Get Form Data
    // ==========================

    $role = trim($_POST['role']);
    $fullname = trim($_POST['fullname']);
    $email = strtolower(trim($_POST['email']));
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ==========================
    // Save Old Values
    // ==========================

    $_SESSION['old_role'] = $role;
    $_SESSION['old_fullname'] = $fullname;
    $_SESSION['old_email'] = $email;
    $_SESSION['old_phone'] = $phone;

    // ==========================
    // User Type Validation
    // ==========================

    if(!in_array($role,["customer","owner"]))
    {
        $_SESSION['error']="Please select a valid User Type.";
        header("Location: register.php");
        exit();
    }

    // ==========================
    // Full Name Validation
    // ==========================

    if(empty($fullname))
    {
        $_SESSION['fullname_error']="Full Name is required.";
        header("Location: register.php");
        exit();
    }

    if(!preg_match("/^[A-Za-z ]+$/",$fullname))
    {
        $_SESSION['fullname_error']="Only letters and spaces are allowed.";
        header("Location: register.php");
        exit();
    }

    // ==========================
    // Email Validation
    // ==========================

    if(empty($email))
    {
        $_SESSION['email_error']="Email Address is required.";
        header("Location: register.php");
        exit();
    }

    if(!preg_match('/^[a-z0-9._%+-]+@gmail\.com$/',$email))
    {
        $_SESSION['email_error']="Only lowercase Gmail addresses are allowed.";
        header("Location: register.php");
        exit();
    }

    // ==========================
    // Phone Validation
    // ==========================

    if(empty($phone))
    {
        $_SESSION['phone_error']="Phone Number is required.";
        header("Location: register.php");
        exit();
    }

    if(!preg_match('/^[0-9]{10}$/',$phone))
    {
        $_SESSION['phone_error']="Phone Number must contain exactly 10 digits.";
        header("Location: register.php");
        exit();
    }
        // ==========================
    // Password Validation
    // ==========================

    if(empty($password))
    {
        $_SESSION['password_error'] = "Password is required.";
        header("Location: register.php");
        exit();
    }

    if(strlen($password) < 8)
    {
        $_SESSION['password_error'] = "Password must be at least 8 characters.";
        header("Location: register.php");
        exit();
    }

    if(!preg_match('/[A-Z]/', $password))
    {
        $_SESSION['password_error'] = "Password must contain at least one uppercase letter.";
        header("Location: register.php");
        exit();
    }

    if(!preg_match('/[a-z]/', $password))
    {
        $_SESSION['password_error'] = "Password must contain at least one lowercase letter.";
        header("Location: register.php");
        exit();
    }

    if(!preg_match('/[0-9]/', $password))
    {
        $_SESSION['password_error'] = "Password must contain at least one number.";
        header("Location: register.php");
        exit();
    }

    if(!preg_match('/[\W_]/', $password))
    {
        $_SESSION['password_error'] = "Password must contain at least one special character.";
        header("Location: register.php");
        exit();
    }

    // ==========================
    // Confirm Password
    // ==========================

    if(empty($confirm_password))
    {
        $_SESSION['confirm_password_error'] = "Confirm Password is required.";
        header("Location: register.php");
        exit();
    }

    if($password != $confirm_password)
    {
        $_SESSION['confirm_password_error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // ==========================
    // Check Duplicate Email
    // ==========================

    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();

    $result = $check->get_result();

    if($result->num_rows > 0)
    {
        $_SESSION['email_error'] = "Email is already registered.";
        header("Location: register.php");
        exit();
    }
        // ==========================
    // Customer = Approved
    // Owner = Pending
    // ==========================

    if($role == "customer")
    {
        $status = "Approved";
    }
    else
    {
        $status = "Pending";
    }

    // ==========================
    // Hash Password
    // ==========================

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ==========================
    // Insert User
    // ==========================

    $stmt = $conn->prepare("
        INSERT INTO users
        (
            fullname,
            email,
            phone,
            password,
            role,
            status
        )
        VALUES
        (
            ?,?,?,?,?,?
        )
    ");

    $stmt->bind_param(
        "ssssss",
        $fullname,
        $email,
        $phone,
        $hashed_password,
        $role,
        $status
    );

    // ==========================
    // Execute Registration
    // ==========================

    if($stmt->execute())
    {
        // Clear old form values
        unset($_SESSION['old_role']);
        unset($_SESSION['old_fullname']);
        unset($_SESSION['old_email']);
        unset($_SESSION['old_phone']);

        // Registration Success Message
        if($role == "owner")
        {
            $_SESSION['success'] =
            "Registration Successful! Your account is waiting for Admin approval.";
        }
        else
        {
            $_SESSION['success'] =
            "Registration Successful! You can now login.";
        }

        header("Location: login.php");
        exit();
    }
    else
    {
        $_SESSION['error'] =
        "Registration Failed. Please try again.";

        header("Location: register.php");
        exit();
    }

    // Close Database
    $stmt->close();
    $check->close();
    $conn->close();
}
else
{
    header("Location: register.php");
    exit();
}
?>