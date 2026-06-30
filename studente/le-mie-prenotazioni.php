<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// TODO Fase 3: caricare le prenotazioni con $dbh->getPrenotazioniByUser($_SESSION["idutente"]).
$templateParams["titolo"] = "Le mie prenotazioni - Campi Sportivi del Campus";
$templateParams["nome"]   = "studente/lista-prenotazioni.php";

require __DIR__ . '/../template/base.php';
?>
