<?php
session_start();
session_destroy();

// Hapus cookie
setcookie('email', '', time() - 3600, "/");
setcookie('password', '', time() - 3600, "/");

header("Location: login.php");
exit;
?>