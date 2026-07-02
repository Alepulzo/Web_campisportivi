<?php
// AREA ADMIN — salva una prenotazione (aggiunta o modifica). Agisce e fa redirect.
require_once __DIR__ . '/../bootstrap.php';

// Riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Accetto solo richieste POST.
if($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("location: " . BASE_URL . "admin/gestione-prenotazioni.php");
    exit;
}

// Leggo i dati del form
$idprenotazione  = isset($_POST["idprenotazione"]) ? intval($_POST["idprenotazione"]) : 0;
$utente          = intval($_POST["utente"] ?? 0);
$campo           = intval($_POST["campo"] ?? 0);
$data            = $_POST["dataprenotazione"] ?? "";
$orario          = $_POST["orario"] ?? "";   // ora di inizio della fascia (es. "14:00")
$numpartecipanti = intval($_POST["numpartecipanti"] ?? 0);
$modifica        = ($idprenotazione > 0);

// Validazione minima.
if($utente <= 0 || $campo <= 0 || $data === "" || $orario === "" || $numpartecipanti <= 0){
    header("location: " . BASE_URL . "admin/gestisci-prenotazione.php" . ($modifica ? "?id=" . $idprenotazione : ""));
    exit;
}

// Una fascia dura 1 ora.
$orainizio = $orario;
$orafine   = str_pad(intval(substr($orario, 0, 2)) + 1, 2, "0", STR_PAD_LEFT) . ":00";

// Controllo le regole (funzione condivisa). Se non vanno, torno al form col messaggio.
$errore = $dbh->erroriPrenotazione($utente, $campo, $data, $orainizio, $orafine, $numpartecipanti, $idprenotazione);
if($errore !== ""){
    $_SESSION["errore_prenotazione"] = $errore;
    header("location: " . BASE_URL . "admin/gestisci-prenotazione.php" . ($modifica ? "?id=" . $idprenotazione : ""));
    exit;
}

if($modifica){
    $dbh->updatePrenotazione($idprenotazione, $utente, $campo, $data, $orainizio, $orafine, $numpartecipanti);
} else {
    $dbh->insertPrenotazione($utente, $campo, $data, $orainizio, $orafine, $numpartecipanti);
}

header("location: " . BASE_URL . "admin/gestione-prenotazioni.php");
exit;
?>
