<?php
require_once __DIR__ . '/../bootstrap.php';

/* AREA ADMIN — elenco campi con azioni CRUD (vista "admin/gestione-campi.php")
 * COSA DEVE FARE:
 *   - $templateParams["campi"] = $dbh->getCampi();
 *   - per ogni campo: link a gestisci-campo.php?action=2&id (Modifica),
 *     gestisci-campo.php?action=3&id (Cancella), chiudi/riapri (setStatoCampo)
 *   - bottone "Aggiungi campo" -> gestisci-campo.php?action=1
 *   - require __DIR__ . '/../template/base.php';
 * TODO: implementare */
?>
