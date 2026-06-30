<?php
/* ============================================================
 * functions.php  —  FUNZIONI DI UTILITÀ
 * ------------------------------------------------------------
 * Piccole funzioni di aiuto usate in tutto il sito.
 * (le altre — getEmptyCampo, getAction, uploadImage — le aggiungeremo
 *  nella Fase 4, quando serviranno per la gestione dei campi)
 * ============================================================ */

// Stampa "active" sulla voce di menu della pagina che stiamo guardando,
// così Bootstrap la evidenzia. basename($_SERVER['PHP_SELF']) = file corrente.
function isActive($pagename){
    if(basename($_SERVER['PHP_SELF']) == $pagename){
        echo "active";
    }
}

// true se in sessione c'è un utente loggato (al login salviamo il suo id).
function isUserLoggedIn(){
    return !empty($_SESSION['idutente']);
}

// true se l'utente loggato è uno studente.
function isStudente(){
    return isUserLoggedIn() && $_SESSION['ruolo'] === 'studente';
}

// true se l'utente loggato è un amministratore.
function isAdmin(){
    return isUserLoggedIn() && $_SESSION['ruolo'] === 'admin';
}

// Salva i dati dell'utente nella sessione (si chiama al momento del login).
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
?>
