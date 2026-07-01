<?php
// AREA ADMIN — esegue il salvataggio/eliminazione di un campo. agisce sul database e poi fa un redirect.
require_once __DIR__ . '/../bootstrap.php';

// Riservata agli admin loggati
if(!isAdmin()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Accetto solo richieste POST.
if($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("location: " . BASE_URL . "admin/gestione-campi.php");
    exit;
}

// CANCELLAZIONE (arriva dal bottone "Cancella" della lista)
if(isset($_POST["azione"]) && $_POST["azione"] === "elimina"){
    $idcampo = intval($_POST["idcampo"]);
    $dbh->deleteCampo($idcampo);
    header("location: " . BASE_URL . "admin/gestione-campi.php");
    exit;
}

// SALVATAGGIO (aggiunta o modifica, arriva dal form gestisci-campo)
$idcampo        = isset($_POST["idcampo"]) ? intval($_POST["idcampo"]) : 0;
$nomecampo      = trim($_POST["nomecampo"] ?? "");
$sport          = intval($_POST["sport"] ?? 0);
$tipocampo      = $_POST["tipocampo"] ?? "";
$luogocampo     = trim($_POST["luogocampo"] ?? "");
$capienzamax    = intval($_POST["capienzamax"] ?? 0);
$orarioapertura = $_POST["orarioapertura"] ?? "";
$orariochiusura = $_POST["orariochiusura"] ?? "";
$descrizione    = trim($_POST["descrizionecampo"] ?? "");
$aperto         = isset($_POST["aperto"]) ? 1 : 0;
$modifica       = ($idcampo > 0);

// Validazione minima lato server.
// Se qualcosa non va, torno al form (vuoto per l'aggiunta, con l'id per la modifica).
if($nomecampo === "" || $sport <= 0 || $luogocampo === "" || $capienzamax <= 0
   || ($tipocampo !== "indoor" && $tipocampo !== "outdoor")
   || $orarioapertura === "" || $orariochiusura === ""){
    header("location: " . BASE_URL . "admin/gestisci-campo.php" . ($modifica ? "?id=" . $idcampo : ""));
    exit;
}

// Se è stata caricata una nuova foto, la salvo e ottengo il nome del file
$nuovaImg = uploadImage($_FILES["imgcampo"] ?? null);

if($modifica){
    if($nuovaImg === null){
        $vecchio  = $dbh->getCampoById($idcampo);
        $nuovaImg = $vecchio["imgcampo"];
    }
    $dbh->updateCampo($idcampo, $nomecampo, $descrizione, $luogocampo, $tipocampo,
                      $capienzamax, $orarioapertura, $orariochiusura,
                      $aperto, $nuovaImg, $sport);
} else {
    if($nuovaImg === null){
        header("location: " . BASE_URL . "admin/gestisci-campo.php");
        exit;
    }
    $creatore = $_SESSION["idutente"];
    $dbh->insertCampo($nomecampo, $descrizione, $luogocampo, $tipocampo,
                      $capienzamax, $orarioapertura, $orariochiusura,
                      $aperto, $nuovaImg, $sport, $creatore);
}

header("location: " . BASE_URL . "admin/gestione-campi.php");
exit;
?>
