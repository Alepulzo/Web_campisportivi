<?php
/* Vista: form per aggiungere o modificare una prenotazione (stesso form per i due casi). */
$prenotazione = $templateParams["prenotazione"];
$studenti     = $templateParams["studenti"];
$campi        = $templateParams["campi"];
$modifica     = !empty($prenotazione["idprenotazione"]);
?>

<h1 class="h3 mb-4"><?php echo $modifica ? "Modifica prenotazione" : "Aggiungi prenotazione"; ?></h1>

<?php if(!empty($templateParams["errore"])): ?>
    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($templateParams["errore"]); ?></div>
<?php endif; ?>

<form method="post" action="<?php echo BASE_URL; ?>admin/processa-prenotazione.php" class="row g-3">

    <!-- idprenotazione nascosto: vuoto = nuova, pieno = modifica -->
    <input type="hidden" name="idprenotazione" value="<?php echo htmlspecialchars($prenotazione["idprenotazione"]); ?>" />

    <div class="col-12 col-md-6">
        <label for="utente" class="form-label">Studente</label>
        <select class="form-select" id="utente" name="utente" required>
            <option value="">— scegli —</option>
            <?php foreach($studenti as $s): ?>
                <option value="<?php echo $s["idutente"]; ?>"
                    <?php echo ($prenotazione["utente"] == $s["idutente"]) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($s["cognome"] . " " . $s["nome"]); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 col-md-6">
        <label for="campo" class="form-label">Campo</label>
        <select class="form-select" id="campo" name="campo" required>
            <option value="">— scegli —</option>
            <?php foreach($campi as $c): ?>
                <option value="<?php echo $c["idcampo"]; ?>"
                    data-capienza="<?php echo $c["capienzamax"]; ?>"
                    <?php echo ($prenotazione["campo"] == $c["idcampo"]) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($c["nomecampo"]); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 col-md-4">
        <label for="dataprenotazione" class="form-label">Data</label>
        <input type="date" class="form-control" id="dataprenotazione" name="dataprenotazione"
               min="<?php echo date('Y-m-d'); ?>"
               max="<?php echo date('Y-m-d', strtotime('+' . GIORNI_ANTICIPO . ' days')); ?>"
               required value="<?php echo htmlspecialchars($prenotazione["dataprenotazione"]); ?>" />
    </div>

    <div class="col-12 col-md-4">
        <label for="orario" class="form-label">Orario</label>
        <!-- si riempie via AJAX (js/form-prenotazione.js) con le fasce LIBERE del campo nel giorno scelto.
             data-selezionato: in modifica ri-seleziona la fascia attuale. data-url: l'endpoint da chiamare. -->
        <select class="form-select" id="orario" name="orario" required
                data-selezionato="<?php echo substr($prenotazione["orainizio"], 0, 5); ?>"
                data-url="<?php echo BASE_URL; ?>api/disponibilita.php">
            <option value="">— scegli campo e giorno —</option>
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
        <button type="submit" class="btn btn-primary">Salva</button>
        <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>admin/gestione-prenotazioni.php">Annulla</a>
    </div>
</form>
