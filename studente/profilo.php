<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// TODO Fase 3: caricare i dati del profilo con $dbh->getUserById($_SESSION["idutente"]).
$templateParams["titolo"] = "Profilo - Campi Sportivi del Campus";
$templateParams["nome"]   = "studente/dettaglio-profilo.php";

require __DIR__ . '/../template/base.php';
?>
