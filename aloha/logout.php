<?php
// 1. Start the session to access it
session_start();

// 2. Unset all session variables
$_SESSION = array();

// 3. Destroy the session on the server
session_destroy();

// 4. Redirect to the landing page (index.php)
header("Location: index.php");
exit();
?>