<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Se arriva un id siamo in MODIFICA: carico la prenotazione per pre-compilare il form.
// Se NON arriva nessun id siamo in AGGIUNTA: parto da una prenotazione "vuota".
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if($id > 0){
    $prenotazione = $dbh->getPrenotazioneById($id);
    if($prenotazione === null){
        header("location: " . BASE_URL . "admin/gestione-prenotazioni.php");
        exit;
    }
} else {
    $prenotazione = getEmptyPrenotazione();
}

$modifica = ($id > 0);

$templateParams["titolo"]       = ($modifica ? "Modifica prenotazione" : "Aggiungi prenotazione") . " - Campi Sportivi del Campus";
$templateParams["nome"]         = "admin/form-prenotazione.php";
$templateParams["prenotazione"] = $prenotazione;             // dati (vuoti o pieni)
$templateParams["studenti"]     = $dbh->getStudenti();       // menu a tendina studente
$templateParams["campi"]        = $dbh->getCampiAperti();    // nel menù solo i campi aperti
$templateParams["js"]           = ["js/form-prenotazione.js"];

// eventuale messaggio d'errore da un salvataggio rifiutato
if(isset($_SESSION["errore_prenotazione"])){
    $templateParams["errore"] = $_SESSION["errore_prenotazione"];
    unset($_SESSION["errore_prenotazione"]);
}

require __DIR__ . '/../template/base.php';
?>
