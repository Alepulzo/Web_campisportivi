<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Prendo gli utenti e li passo alla vista.
$templateParams["titolo"] = "Gestione utenti - Campi Sportivi del Campus";
$templateParams["nome"]   = "admin/lista-utenti.php";
$templateParams["utenti"] = $dbh->getAllUtenti();
$templateParams["js"]     = ["js/gestione-utenti.js"];

require __DIR__ . '/../template/base.php';
?>
