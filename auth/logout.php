<?php
session_start();

// hapus semua data session
$_SESSION = [];

// hancurkan session
session_destroy();

// redirect ke login + pesan logout
header("Location: login.php?logout=1");
exit;
?>