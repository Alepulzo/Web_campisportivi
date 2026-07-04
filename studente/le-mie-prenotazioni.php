<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Prendo tutte le prenotazioni dello studente e le divido in oggi / future / passate.
$tutte   = $dbh->getPrenotazioniByUser($_SESSION["idutente"]);
$oggi    = [];
$future  = [];
$passate = [];
$today   = date("Y-m-d");

foreach($tutte as $p){
    if($p["dataprenotazione"] === $today){
        $oggi[] = $p;
    } elseif($p["dataprenotazione"] > $today){
        $future[] = $p;
    } else {
        $passate[] = $p;
    }
}
$passate = array_reverse($passate);   // le passate più recenti in alto

$templateParams["titolo"]  = "Le mie prenotazioni - Campi Sportivi del Campus";
$templateParams["nome"]    = "studente/lista-prenotazioni.php";
$templateParams["oggi"]    = $oggi;
$templateParams["future"]  = $future;
$templateParams["passate"] = $passate;

require __DIR__ . '/../template/base.php';
?>
