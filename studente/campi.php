<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Solo i campi APERTI: uno studente non deve poter prenotare un campo chiuso.
$templateParams["titolo"] = "Prenota un campo - Campi Sportivi del Campus";
$templateParams["nome"]   = "studente/lista-campi.php";
$templateParams["campi"]  = array_values(array_filter($dbh->getCampi(), function($campo){
    return $campo["aperto"] == 1;
}));

require __DIR__ . '/../template/base.php';
?>
