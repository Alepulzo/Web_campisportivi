<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Prendo i campi e li passo alla vista.
$templateParams["titolo"] = "Gestione campi - Campi Sportivi del Campus";
$templateParams["nome"]   = "admin/gestione-campi.php";
$templateParams["campi"]  = $dbh->getCampi();
$templateParams["js"]     = ["js/gestione-campi.js"];

require __DIR__ . '/../template/base.php';
?>
