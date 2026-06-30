<?php
require_once __DIR__ . '/../bootstrap.php';

/* ENDPOINT AJAX (JSON) — orari liberi/occupati di un campo in una data
 * URL: api/disponibilita.php?id=...&data=AAAA-MM-GG
 *   - calcola le fasce libere usando getPrenotazioniByCampoEData(...)
 *   - restituisce il risultato in JSON
 * È la base per il "calendario" interattivo (effetto WOW).
 * TODO: implementare */
?>
