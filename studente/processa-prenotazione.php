<?php
require_once __DIR__ . '/../bootstrap.php';

/* AREA STUDENTE — elabora prenotazione/annullamento (NON mostra HTML: agisce e redirect)
 * COSA DEVE FARE:
 *   - controllare che sia uno studente loggato
 *   - leggere dal POST: campo, data, ora inizio/fine, azione (prenota/annulla)
 *   - isSlotLibero(...) poi insertPrenotazione(...) / annullaPrenotazione(...)
 *   - header("location: ".BASE_URL."studente/le-mie-prenotazioni.php");
 * TODO: implementare */
?>
