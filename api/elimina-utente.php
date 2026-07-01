<?php
// Endpoint JSON: elimina uno studente. Chiamato via fetch() da gestione-utenti.js.
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

// Solo admin.
if(!isAdmin()){
    http_response_code(403);
    echo json_encode(["success" => false, "errore" => "Non autorizzato"]);
    exit;
}

// Leggo l'id dell'utente da eliminare
$idutente = isset($_POST["idutente"]) ? intval($_POST["idutente"]) : 0;

if($idutente <= 0){
    http_response_code(400);
    echo json_encode(["success" => false, "errore" => "Utente non valido"]);
    exit;
}

// deleteUtente elimina solo gli studenti (mai un admin)
$ok = $dbh->deleteUtente($idutente);
echo json_encode(["success" => $ok]);
?>
