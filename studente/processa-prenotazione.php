<?php
// AREA STUDENTE — elabora prenotazione/annullamento. Agisce e fa redirect (niente HTML).
require_once __DIR__ . '/../bootstrap.php';

// Riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Accetto solo richieste POST.
if($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("location: " . BASE_URL . "studente/le-mie-prenotazioni.php");
    exit;
}

$idutente       = $_SESSION["idutente"];   // MAI dal POST: lo studente prenota solo per sé
$idprenotazione = isset($_POST["idprenotazione"]) ? intval($_POST["idprenotazione"]) : 0;
$azione         = $_POST["azione"] ?? "prenota";

// ANNULLA una prenotazione propria 
if($azione === "annulla"){
    if($idprenotazione > 0){
        $dbh->annullaPrenotazione($idprenotazione, $idutente);
    }
    header("location: " . BASE_URL . "studente/le-mie-prenotazioni.php");
    exit;
}

// PRENOTA (nuova) o MODIFICA
$campo           = intval($_POST["campo"] ?? 0);
$data            = $_POST["dataprenotazione"] ?? "";
$orario          = $_POST["orario"] ?? "";   // ora di inizio della fascia (es. "14:00")
$numpartecipanti = intval($_POST["numpartecipanti"] ?? 0);
$modifica        = ($idprenotazione > 0);

// In modifica, la prenotazione deve esistere ed essere davvero dello studente loggato.
if($modifica){
    $esistente = $dbh->getPrenotazioneById($idprenotazione);
    if($esistente === null || $esistente["utente"] != $idutente){
        header("location: " . BASE_URL . "studente/le-mie-prenotazioni.php");
        exit;
    }
}

// Validazione minima.
if($campo <= 0 || $data === "" || $orario === "" || $numpartecipanti <= 0){
    header("location: " . BASE_URL . "studente/gestisci-prenotazione.php" . ($modifica ? "?id=" . $idprenotazione : "?campo=" . $campo));
    exit;
}

// Una fascia dura 1 ora.
$orainizio = $orario;
$orafine   = str_pad(intval(substr($orario, 0, 2)) + 1, 2, "0", STR_PAD_LEFT) . ":00";

// Controllo le regole (funzione condivisa con l'admin). Se non vanno, torno al form col messaggio.
$errore = $dbh->erroriPrenotazione($idutente, $campo, $data, $orainizio, $orafine, $numpartecipanti, $idprenotazione);
if($errore !== ""){
    $_SESSION["errore_prenotazione"] = $errore;
    header("location: " . BASE_URL . "studente/gestisci-prenotazione.php" . ($modifica ? "?id=" . $idprenotazione : "?campo=" . $campo));
    exit;
}

if($modifica){
    $dbh->updatePrenotazione($idprenotazione, $idutente, $campo, $data, $orainizio, $orafine, $numpartecipanti);
} else {
    $dbh->insertPrenotazione($idutente, $campo, $data, $orainizio, $orafine, $numpartecipanti);
}

header("location: " . BASE_URL . "studente/le-mie-prenotazioni.php");
exit;
?>
