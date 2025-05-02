<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php"); // Arahkan user ke halaman login
exit;
?>