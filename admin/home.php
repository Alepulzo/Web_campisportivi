<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Prendo i numeri e le prenotazioni di oggi per la dashboard.
$templateParams["titolo"] = "Dashboard Admin - Campi Sportivi del Campus";
$templateParams["nome"]   = "admin/dashboard.php";
$templateParams["numCampi"]         = $dbh->countCampi();
$templateParams["numCampiChiusi"]   = $dbh->countCampiChiusi();
$templateParams["prenotazioniOggi"] = $dbh->getPrenotazioniOggi();

require __DIR__ . '/../template/base.php';
?>
