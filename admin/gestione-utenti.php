<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// TODO Fase 4: caricare gli utenti con $dbh->getAllUtenti().
$templateParams["titolo"] = "Gestione utenti - Campi Sportivi del Campus";
$templateParams["nome"]   = "admin/gestione-utenti.php";

require __DIR__ . '/../template/base.php';
?>
