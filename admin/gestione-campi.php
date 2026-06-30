<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// TODO Fase 4: caricare i campi con $dbh->getCampi() e mostrarli con i bottoni di gestione.
$templateParams["titolo"] = "Gestione campi - Campi Sportivi del Campus";
$templateParams["nome"]   = "admin/gestione-campi.php";

require __DIR__ . '/../template/base.php';
?>
