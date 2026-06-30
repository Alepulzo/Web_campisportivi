<?php
require_once __DIR__ . '/../bootstrap.php';

/* AREA ADMIN — mostra il form per gestire un campo (vista "admin/form-campo.php")
 * URL: admin/gestisci-campo.php?action=1            (1 = Inserisci)
 *      admin/gestisci-campo.php?action=2&id=5       (2 = Modifica)
 *      admin/gestisci-campo.php?action=3&id=5       (3 = Cancella)
 * COSA DEVE FARE:
 *   - controllare isAdmin() e la validità di action/id
 *   - se action != 1: caricare il campo; altrimenti getEmptyCampo()
 *   - require __DIR__ . '/../template/base.php';
 * TODO: implementare */
?>
