<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

$templateParams["titolo"] = "Dashboard - Campi Sportivi del Campus";
$templateParams["nome"]   = "studente/home.php";

require __DIR__ . '/../template/base.php';
?>
