<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Dettaglio di un campo. URL: studente/campo.php?id=X
$id    = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$campo = $id > 0 ? $dbh->getCampoById($id) : null;

if($campo === null){
    header("location: " . BASE_URL . "studente/campi.php");
    exit;
}

$templateParams["titolo"] = "Dettaglio campo - Campi Sportivi del Campus";
$templateParams["nome"]   = "studente/singolo-campo.php";
$templateParams["campo"]  = $campo;

require __DIR__ . '/../template/base.php';
?>
