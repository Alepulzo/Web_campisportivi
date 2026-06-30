<?php
require_once 'bootstrap.php';

// Esce: svuota la sessione e torna al login.
logout();
header("location: " . BASE_URL . "index.php");
exit;
?>
