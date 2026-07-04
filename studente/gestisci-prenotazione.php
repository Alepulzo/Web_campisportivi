<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

$idCampo         = isset($_GET["campo"]) ? intval($_GET["campo"]) : 0;
$idPrenotazione  = isset($_GET["id"])    ? intval($_GET["id"])    : 0;

if($idPrenotazione > 0){
    // MODIFICA: la prenotazione deve esistere e appartenere allo studente loggato.
    $prenotazione = $dbh->getPrenotazioneById($idPrenotazione);
    if($prenotazione === null || $prenotazione["utente"] != $_SESSION["idutente"]){
        header("location: " . BASE_URL . "studente/le-mie-prenotazioni.php");
        exit;
    }
    $campo = $dbh->getCampoById($prenotazione["campo"]);
} elseif($idCampo > 0){
    // NUOVA: il campo arriva dal bottone "Prenota" e deve essere aperto.
    $campo = $dbh->getCampoById($idCampo);
    if($campo === null || $campo["aperto"] == 0){
        header("location: " . BASE_URL . "studente/campi.php");
        exit;
    }
    $prenotazione = getEmptyPrenotazione();
    $prenotazione["campo"] = $campo["idcampo"];
} else {
    // Né id né campo: non c'è niente da gestire.
    header("location: " . BASE_URL . "studente/campi.php");
    exit;
}

$modifica = ($idPrenotazione > 0);

$templateParams["titolo"]       = ($modifica ? "Modifica prenotazione" : "Prenota un campo") . " - Campi Sportivi del Campus";
$templateParams["nome"]         = "studente/form-prenotazione.php";
$templateParams["prenotazione"] = $prenotazione;
$templateParams["campo"]        = $campo;
$templateParams["js"]           = ["js/form-prenotazione.js"];

// eventuale messaggio d'errore da un salvataggio rifiutato
if(isset($_SESSION["errore_prenotazione"])){
    $templateParams["errore"] = $_SESSION["errore_prenotazione"];
    unset($_SESSION["errore_prenotazione"]);
}

require __DIR__ . '/../template/base.php';
?>
