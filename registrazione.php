<?php
require_once 'bootstrap.php';

if(isUserLoggedIn()){
    header("location: " . BASE_URL . (isAdmin() ? "admin/home.php" : "studente/home.php"));
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome     = trim($_POST["nome"]);
    $cognome  = trim($_POST["cognome"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $conferma = $_POST["conferma"];

    // Controlli (validazione lato server)
    if($nome == "" || $cognome == "" || $email == "" || $password == ""){
        $templateParams["errore"] = "Compila tutti i campi.";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $templateParams["errore"] = "L'email non è valida.";
    } else if(!str_ends_with(strtolower($email), "@studio.unibo.it")){
        $templateParams["errore"] = "Per registrarti devi usare la tua email istituzionale (@studio.unibo.it).";
    } else if($password !== $conferma){
        $templateParams["errore"] = "Le due password non coincidono.";
    } else if(count($dbh->getUserByEmail($email)) > 0){
        $templateParams["errore"] = "Questa email è già registrata.";
    } else {
        $id = $dbh->registerUser($nome, $cognome, $email, $password);
        if($id != false){
            // login automatico subito dopo la registrazione
            registerLoggedUser(array(
                "idutente" => $id, "nome" => $nome, "cognome" => $cognome,
                "email" => $email, "ruolo" => "studente"
            ));
            header("location: " . BASE_URL . "studente/home.php");
            exit;
        } else {
            $templateParams["errore"] = "Errore durante la registrazione, riprova.";
        }
    }
}

$templateParams["titolo"] = "Registrati - Campi Sportivi del Campus";
$templateParams["nome"]   = "auth/registrazione-form.php";

require 'template/base.php';
?>
