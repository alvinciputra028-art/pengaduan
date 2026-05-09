<?php
session_start();

// hapus semua data session
$_SESSION = [];

// hancurkan session
session_destroy();

header("Location: login.php?logout=1");
exit;
?>