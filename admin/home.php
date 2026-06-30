<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

$templateParams["titolo"] = "Dashboard Admin - Campi Sportivi del Campus";
$templateParams["nome"]   = "admin/dashboard.php";

require __DIR__ . '/../template/base.php';
?>
