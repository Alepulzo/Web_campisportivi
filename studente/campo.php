<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// TODO: dettaglio di un campo. URL: studente/campo.php?id=X
//   - $campo = $dbh->getCampoById($id);  (se null -> torna a campi.php)
//   - la vista mostra foto/descrizione/orari + un bottone "Prenota"
//     che porta a gestisci-prenotazione.php?campo=X
$templateParams["titolo"] = "Dettaglio campo - Campi Sportivi del Campus";
$templateParams["nome"]   = "studente/singolo-campo.php";

require __DIR__ . '/../template/base.php';
?>
