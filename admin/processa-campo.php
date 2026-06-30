<?php
require_once __DIR__ . '/../bootstrap.php';

/* AREA ADMIN — esegue il CRUD del campo (NON mostra HTML: agisce e fa redirect)
 * COSA DEVE FARE in base a $_POST["action"]:
 *   1) Inserisci -> uploadImage(...) + $dbh->insertCampo(...)
 *   2) Modifica  -> (eventuale nuova immagine) + $dbh->updateCampo(...)
 *   3) Cancella  -> $dbh->deleteCampo(...)
 *   (chiudi/riapri campo -> $dbh->setStatoCampo(...))
 *   - header("location: ".BASE_URL."admin/gestione-campi.php?formmsg=...");
 * TODO: implementare */
?>
