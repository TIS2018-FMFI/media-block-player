<?php
session_start();
unset($_SESSION['login_user']);
unset($_SESSION['id']);
unset($_SESSION['admin']);
session_destroy();
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