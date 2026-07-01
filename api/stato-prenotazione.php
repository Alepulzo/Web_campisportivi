<?php
// Endpoint JSON: annulla una prenotazione. Chiamato via fetch() da gestione-prenotazioni.js.
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

// Solo admin.
if(!isAdmin()){
    http_response_code(403);
    echo json_encode(["success" => false, "errore" => "Non autorizzato"]);
    exit;
}

// Leggo l'id della prenotazione da annullare
$idprenotazione = isset($_POST["idprenotazione"]) ? intval($_POST["idprenotazione"]) : 0;

if($idprenotazione <= 0){
    http_response_code(400);
    echo json_encode(["success" => false, "errore" => "Prenotazione non valida"]);
    exit;
}

// Aggiorno il DB e rispondo in JSON
$ok = $dbh->annullaPrenotazioneAdmin($idprenotazione);
echo json_encode(["success" => $ok]);
?>
