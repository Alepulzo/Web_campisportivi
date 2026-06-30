<?php
require_once __DIR__ . '/../bootstrap.php';

/* AREA STUDENTE — elenco campi da prenotare (vista "studente/lista-campi.php")
 * Sicurezza: solo studente loggato.
 * COSA DEVE FARE:
 *   - $templateParams["campi"] = $dbh->getCampi();   // filtrabile con ?sport=ID
 *   - $templateParams["sport"] = $dbh->getSport();   // per il filtro
 *   - require __DIR__ . '/../template/base.php';
 * TODO: implementare */
?>
