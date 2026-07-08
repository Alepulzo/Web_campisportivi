<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

$idutente = $_SESSION["idutente"];

$templateParams["titolo"]           = "Dashboard - Campi Sportivi del Campus";
$templateParams["nome"]             = "studente/dashboard.php";
$templateParams["prossime"]         = $dbh->getProssimePrenotazioni($idutente, 5);
$templateParams["numPrenotazioni"]  = $dbh->countPrenotazioniByUser($idutente);
$templateParams["numCampiAperti"]   = $dbh->countCampiAperti();

require __DIR__ . '/../template/base.php';
?>
