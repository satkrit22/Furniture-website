<?php
session_start();

// Clear the session
$_SESSION = array();

// Destroy the session
session_destroy();

// Prevent the browser from caching the page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Redirect to the login page
header("Location: login.php");
exit();
?>
