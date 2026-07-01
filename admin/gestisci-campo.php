<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Se arriva un id siamo in MODIFICA: carico quel campo per pre-compilare il form.
// Se NON arriva nessun id siamo in AGGIUNTA: parto da un campo "vuoto".
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if($id > 0){
    $campo = $dbh->getCampoById($id);
    // se l'id non esiste, torno alla lista invece di mostrare un form rotto
    if($campo === null){
        header("location: " . BASE_URL . "admin/gestione-campi.php");
        exit;
    }
} else {
    $campo = getEmptyCampo();
}

$modifica = ($id > 0);

$templateParams["titolo"] = ($modifica ? "Modifica campo" : "Aggiungi campo") . " - Campi Sportivi del Campus";
$templateParams["nome"]   = "admin/form-campo.php";
$templateParams["campo"]  = $campo;                 // dati del campo (vuoti o pieni)
$templateParams["sport"]  = $dbh->getSport();       // per il menu a tendina

require __DIR__ . '/../template/base.php';
?>
