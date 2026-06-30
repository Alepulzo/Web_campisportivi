<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// TODO Fase 4: caricare tutte le prenotazioni con $dbh->getAllPrenotazioni().
$templateParams["titolo"] = "Gestione prenotazioni - Campi Sportivi del Campus";
$templateParams["nome"]   = "admin/gestione-prenotazioni.php";

require __DIR__ . '/../template/base.php';
?>
