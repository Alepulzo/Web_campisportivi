<?php
require_once __DIR__ . '/../bootstrap.php';

/* AREA ADMIN — tutte le prenotazioni (vista "admin/gestione-prenotazioni.php")
 * COSA DEVE FARE:
 *   - $templateParams["prenotazioni"] = $dbh->getAllPrenotazioni();  // join utente + campo
 *   - (eventuale filtro per campo/data)
 *   - ogni riga con possibilità di ANNULLARE (link/form -> processa-prenotazione.php)
 *   - require __DIR__ . '/../template/base.php';
 * TODO: implementare */
?>
