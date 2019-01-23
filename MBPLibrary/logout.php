<?php

/**
 * @author Martin Hrebeňár
 */

session_start();
unset($_SESSION['login_user']);
unset($_SESSION['id']);
unset($_SESSION['admin']);

$_SESSION['msg'] = "You have been logged off.";
$_SESSION['msg_status'] = "OK";
header('location: index.php');
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <title>LogOut</title>
</head>
<body>
</body>
</html>