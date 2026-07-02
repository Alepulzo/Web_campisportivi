<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Prendo tutte le prenotazioni e le divido in oggi / future / passate.
$tutte   = $dbh->getAllPrenotazioni();
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

$templateParams["titolo"]  = "Gestione prenotazioni - Campi Sportivi del Campus";
$templateParams["nome"]    = "admin/lista-prenotazioni.php";
$templateParams["oggi"]    = $oggi;
$templateParams["future"]  = $future;
$templateParams["passate"] = $passate;
$templateParams["js"]      = ["js/gestione-prenotazioni.js"];

require __DIR__ . '/../template/base.php';
?>
