<?php
// Endpoint JSON: cambia lo stato di un campo. Chiamato via fetch() da gestione-campi.js.
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

// Solo admin.
if(!isAdmin()){
    http_response_code(403);
    echo json_encode(["success" => false, "errore" => "Non autorizzato"]);
    exit;
}

// Leggo id e nuovo stato (1 = aperto, 0 = chiuso)
$idcampo = isset($_POST["idcampo"]) ? intval($_POST["idcampo"]) : 0;
$aperto  = isset($_POST["aperto"])  ? intval($_POST["aperto"])  : 0;

if($idcampo <= 0){
    http_response_code(400);
    echo json_encode(["success" => false, "errore" => "Campo non valido"]);
    exit;
}

// Aggiorno il DB e rispondo in JSON
$ok = $dbh->setStatoCampo($idcampo, $aperto);
echo json_encode(["success" => $ok, "aperto" => $aperto]);
?>
