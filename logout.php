<?php
session_start();

// ==============================
// Prevent Browser Cache
// ==============================

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// ==============================
// Clear All Session Variables
// ==============================

$_SESSION = array();

// ==============================
// Destroy Session Cookie
// ==============================

if (ini_get("session.use_cookies"))
{
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// ==============================
// Destroy Session
// ==============================

session_destroy();

// ==============================
// Redirect to Login Page
// ==============================

header("Location: login.php");
exit();
?>