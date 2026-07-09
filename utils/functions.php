<?php
/* functions.php — piccole funzioni di aiuto usate in tutto il sito. */

// Stampa "active" sulla voce di menu della pagina attuale (la evidenzia).
function isActive($pagename){
    if(basename($_SERVER['PHP_SELF']) == $pagename){
        echo "active";
    }
}

// true se c'è un utente loggato.
function isUserLoggedIn(){
    return !empty($_SESSION['idutente']);
}

// true se l'utente loggato è uno studente.
function isStudente(){
    return isUserLoggedIn() && $_SESSION['ruolo'] === 'studente';
}

// true se l'utente loggato è un admin.
function isAdmin(){
    return isUserLoggedIn() && $_SESSION['ruolo'] === 'admin';
}

// Salva i dati dell'utente nella sessione (al login).
function registerLoggedUser($user){
    $_SESSION['idutente'] = $user['idutente'];
    $_SESSION['nome']     = $user['nome'];
    $_SESSION['cognome']  = $user['cognome'];
    $_SESSION['email']    = $user['email'];
    $_SESSION['ruolo']    = $user['ruolo'];
}

// Esce: svuota e distrugge la sessione.
function logout(){
    session_unset();
    session_destroy();
}

// Ritorna un campo "vuoto" (per il form di aggiunta).
function getEmptyCampo(){
    return [
        "idcampo"          => "",
        "nomecampo"        => "",
        "descrizionecampo" => "",
        "luogocampo"       => "",
        "tipocampo"        => "",
        "capienzamax"      => "",
        "orarioapertura"   => "",
        "orariochiusura"   => "",
        "aperto"           => 1,
        "imgcampo"         => "",
        "sport"            => ""
    ];
}

// Ritorna una prenotazione "vuota" (per il form di aggiunta).
function getEmptyPrenotazione(){
    return [
        "idprenotazione"   => "",
        "utente"           => "",
        "campo"            => "",
        "dataprenotazione" => "",
        "orainizio"        => "",
        "orafine"          => "",
        "numpartecipanti"  => 1
    ];
}

// Carica la foto in upload/ e ritorna il nome del file, oppure null.
function uploadImage($file){
    // nessun file o errore nel caricamento
    if(!isset($file) || !isset($file["error"]) || $file["error"] !== UPLOAD_ERR_OK){
        return null;
    }
    // controllo che sia DAVVERO un'immagine (non mi fido del tipo dichiarato dal browser)
    if(getimagesize($file["tmp_name"]) === false){
        return null;
    }
    // accetto solo alcuni tipi di immagine
    $permesse = ["image/jpeg", "image/png", "image/webp"];
    if(!in_array($file["type"], $permesse)){
        return null;
    }
    // nome unico per non sovrascrivere le altre foto
    $ext  = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $nome = "campo_" . uniqid() . "." . $ext;
    // sposto il file in upload/
    if(move_uploaded_file($file["tmp_name"], UPLOAD_PATH . $nome)){
        return $nome;
    }
    return null;
}
?>
