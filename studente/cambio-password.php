<?php
require_once __DIR__ . '/../bootstrap.php';

// Pagina riservata agli studenti loggati
if(!isStudente()){
    header("location: " . BASE_URL . "index.php");
    exit;
}

// Cambio password (invio del form)
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $vecchia  = $_POST["vecchia_password"]  ?? "";
    $nuova    = $_POST["nuova_password"]    ?? "";
    $conferma = $_POST["conferma_password"] ?? "";

    if($vecchia == "" || $nuova == "" || $conferma == ""){
        $templateParams["errore_password"] = "Compila tutti i campi.";
    } else if(!$dbh->checkPasswordById($_SESSION["idutente"], $vecchia)){
        $templateParams["errore_password"] = "La password attuale non è corretta.";
    } else if($nuova !== $conferma){
        $templateParams["errore_password"] = "Le due nuove password non coincidono.";
    } else {
        $dbh->updatePassword($_SESSION["idutente"], $nuova);
        $templateParams["successo_password"] = "Password aggiornata con successo.";
    }
}

$templateParams["titolo"] = "Cambia password - Campi Sportivi del Campus";
$templateParams["nome"]   = "studente/form-cambio-password.php";

require __DIR__ . '/../template/base.php';
?>
