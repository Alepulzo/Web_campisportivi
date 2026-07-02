<?php
// Endpoint JSON: fasce da 1 ora LIBERE di un campo in una certa data.
// Usato dal form prenotazione (admin e studente) via js/form-prenotazione.js.
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

// Basta essere loggati (admin o studente): lo riusano entrambi.
if(!isUserLoggedIn()){
    http_response_code(403);
    echo json_encode(["slot" => []]);
    exit;
}

$idcampo = isset($_GET["campo"])   ? intval($_GET["campo"])   : 0;
$data    = $_GET["data"] ?? "";
$escludi = isset($_GET["escludi"]) ? intval($_GET["escludi"]) : 0;

if($idcampo <= 0 || $data === ""){
    echo json_encode(["slot" => []]);
    exit;
}

$campo = $dbh->getCampoById($idcampo);
if($campo === null){
    echo json_encode(["slot" => []]);
    exit;
}

// oggi e ora attuale: servono a escludere le fasce già passate di oggi
$oggi      = date("Y-m-d");
$oraAdesso = date("H:i");

// prenotazioni già confermate su quel campo/giorno (esclusa quella che sto modificando)
$occupate = $dbh->getPrenotazioniByCampoEData($idcampo, $data, $escludi);

$apertura = intval(substr($campo["orarioapertura"], 0, 2));
$chiusura = intval(substr($campo["orariochiusura"], 0, 2));

$slotLiberi = [];
for($h = $apertura; $h < $chiusura; $h++){
    // se è oggi, salto le fasce già iniziate
    if($data === $oggi && sprintf("%02d:00", $h) <= $oraAdesso){
        continue;
    }

    $inizioSlot = sprintf("%02d:00:00", $h);
    $fineSlot   = sprintf("%02d:00:00", $h + 1);

    // lo slot è libero se nessuna prenotazione si sovrappone:
    // sovrapposizione = inizio prenotazione < fine slot  E  fine prenotazione > inizio slot
    $libero = true;
    foreach($occupate as $o){
        if($o["orainizio"] < $fineSlot && $o["orafine"] > $inizioSlot){
            $libero = false;
            break;
        }
    }
    if($libero){
        $slotLiberi[] = sprintf("%02d:00", $h);
    }
}

echo json_encode(["slot" => $slotLiberi]);
?>
