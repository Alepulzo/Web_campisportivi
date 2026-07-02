<?php
/* ============================================================
 * bootstrap.php  —  FILE DI AVVIO (incluso da OGNI pagina)
 * ------------------------------------------------------------
 * Le pagine stanno in sottocartelle (studente/, admin/, api/),
 * quindi:
 *   - i percorsi dei file usano __DIR__ (percorso assoluto su disco)
 *     così funzionano da qualunque cartella;
 *   - i percorsi web (link, css, js, immagini) usano BASE_URL
 *     così puntano sempre alla radice del sito, non alla sottocartella.
 * ============================================================ */

session_start();

// Radice del sito sotto htdocs. Se rinomini la cartella, cambia QUI.
define("BASE_URL", "/campisportivi/");

// Cartella upload in due forme:
define("UPLOAD_PATH", __DIR__ . "/upload/");   // su disco -> per SALVARE i file caricati
define("UPLOAD_URL",  BASE_URL . "upload/");    // URL      -> per MOSTRARE le immagini <img src>

// Quanti giorni in anticipo si può prenotare (oggi + questi giorni).
define("GIORNI_ANTICIPO", 10);

// __DIR__ = cartella di questo file (la root): così l'include vale da ogni sottocartella.
require_once __DIR__ . "/utils/functions.php";
require_once __DIR__ . "/db/database.php";

$dbh = new DatabaseHelper("localhost", "root", "", "campisportivi", 3306);
?>
