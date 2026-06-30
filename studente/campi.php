<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// TODO Fase 3: caricare i campi con $dbh->getCampi() e gli sport per il filtro.
$templateParams["titolo"] = "Prenota un campo - Campi Sportivi del Campus";
$templateParams["nome"]   = "studente/lista-campi.php";

require __DIR__ . '/../template/base.php';
?>
