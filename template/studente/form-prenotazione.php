<?php
/* Vista: form prenotazione studente (nuova o modifica). Niente scelta studente/campo:
   il campo è già deciso e arriva come <select> con UNA sola opzione, così
   js/form-prenotazione.js (pensato per l'admin) funziona senza modifiche. */
$prenotazione = $templateParams["prenotazione"] ?? array();
$campo        = $templateParams["campo"] ?? array();
$modifica     = !empty($prenotazione["idprenotazione"]);
?>

<h1 class="h3 pb-3 mb-4 border-bottom"><?php echo $modifica ? "Modifica prenotazione" : "Prenota un campo"; ?></h1>

<div class="card shadow-sm border-0" style="max-width: 800px;">
<div class="card-body">
<?php if(!empty($templateParams["errore"])): ?>
    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($templateParams["errore"]); ?></div>
<?php endif; ?>

<p class="mb-4">
    Campo: <strong><?php echo htmlspecialchars($campo["nomecampo"]); ?></strong>
    (<?php echo htmlspecialchars($campo["luogocampo"]); ?>)
</p>

<form method="post" action="<?php echo BASE_URL; ?>studente/processa-prenotazione.php" class="row g-3">

    <!-- idprenotazione nascosto: vuoto = nuova, pieno = modifica -->
    <input type="hidden" name="idprenotazione" value="<?php echo htmlspecialchars($prenotazione["idprenotazione"]); ?>" />

    <!-- campo già deciso: select con una sola opzione (js/form-prenotazione.js legge data-capienza) -->
    <select class="d-none" id="campo" name="campo">
        <option value="<?php echo $campo["idcampo"]; ?>" data-capienza="<?php echo $campo["capienzamax"]; ?>" selected>
            <?php echo htmlspecialchars($campo["nomecampo"]); ?>
        </option>
    </select>

    <div class="col-12 col-md-4">
        <label for="dataprenotazione" class="form-label">Data</label>
        <input type="date" class="form-control" id="dataprenotazione" name="dataprenotazione"
               min="<?php echo date('Y-m-d'); ?>"
               max="<?php echo date('Y-m-d', strtotime('+' . GIORNI_ANTICIPO . ' days')); ?>"
               required value="<?php echo htmlspecialchars($prenotazione["dataprenotazione"]); ?>" />
    </div>

    <div class="col-12 col-md-4">
        <label for="orario" class="form-label">Orario</label>
        <!-- si riempie via AJAX (js/form-prenotazione.js) con le fasce LIBERE del campo nel giorno scelto. -->
        <select class="form-select" id="orario" name="orario" required
                data-selezionato="<?php echo substr($prenotazione["orainizio"], 0, 5); ?>"
                data-url="<?php echo BASE_URL; ?>api/disponibilita.php"
                data-msg-vuoto="— scegli il giorno —">
            <option value="">— scegli il giorno —</option>
        </select>
    </div>

    <div class="col-12 col-md-4">
        <label for="numpartecipanti" class="form-label">Partecipanti</label>
        <input type="number" class="form-control" id="numpartecipanti" name="numpartecipanti"
               min="1" required aria-describedby="capienzaHint"
               value="<?php echo htmlspecialchars($prenotazione["numpartecipanti"]); ?>" />
        <div class="form-text" id="capienzaHint"></div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-dark">Salva</button>
        <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>studente/campi.php">Annulla</a>
    </div>
</form>
</div>
</div>
