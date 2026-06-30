<?php
require_once 'bootstrap.php';

// Se sei già loggato, vai direttamente alla tua area
if(isUserLoggedIn()){
    header("location: " . BASE_URL . (isAdmin() ? "admin/home.php" : "studente/home.php"));
    exit;
}

// Tentativo di login (invio del form)
if(isset($_POST["email"]) && isset($_POST["password"])){
    $risultato = $dbh->checkLogin($_POST["email"], $_POST["password"]);
    if(count($risultato) == 0){
        $templateParams["errorelogin"] = "Email o password non corretti.";
    } else {
        registerLoggedUser($risultato[0]);   // salvo l'utente in sessione
        header("location: " . BASE_URL . ($risultato[0]["ruolo"] == "admin" ? "admin/home.php" : "studente/home.php"));
        exit;
    }
}

$templateParams["titolo"] = "Accedi - Campi Sportivi del Campus";
$templateParams["nome"]   = "auth/login-form.php";

require 'template/base.php';
?>
