<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// TODO: form prenotazione studente. Modello: admin/gestisci-prenotazione.php, ma:
//   - NUOVA: arriva ?campo=X (dal bottone "Prenota") -> campo già fissato ($dbh->getCampoById)
//   - MODIFICA: arriva ?id=Y -> $dbh->getPrenotazioneById() e controlla che sia SUA (utente = sessione)
//   - niente scelta studente (è lui) e niente scelta campo (già deciso)
//   - riusa js/form-prenotazione.js (tieni il campo come <select> con UNA sola opzione)
$templateParams["titolo"] = "Prenota un campo - Campi Sportivi del Campus";
$templateParams["nome"]   = "studente/form-prenotazione.php";

require __DIR__ . '/../template/base.php';
?>
