<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

$templateParams["titolo"]  = "Profilo - Campi Sportivi del Campus";
$templateParams["nome"]    = "studente/dettaglio-profilo.php";
$templateParams["utente"]  = $dbh->getUserById($_SESSION["idutente"]);

require __DIR__ . '/../template/base.php';
?>
