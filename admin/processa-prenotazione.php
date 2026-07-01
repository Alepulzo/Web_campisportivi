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
$orainizio       = $_POST["orainizio"] ?? "";
$orafine         = $_POST["orafine"] ?? "";
$numpartecipanti = intval($_POST["numpartecipanti"] ?? 0);
$modifica        = ($idprenotazione > 0);

// Validazione minima (l'ora fine deve venire dopo l'ora inizio).
if($utente <= 0 || $campo <= 0 || $data === "" || $orainizio === "" || $orafine === ""
   || $numpartecipanti <= 0 || $orafine <= $orainizio){
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
