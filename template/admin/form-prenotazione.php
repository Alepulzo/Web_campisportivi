<?php
/* Vista: form per aggiungere o modificare una prenotazione (stesso form per i due casi). */
$prenotazione = $templateParams["prenotazione"];
$studenti     = $templateParams["studenti"];
$campi        = $templateParams["campi"];
$modifica     = !empty($prenotazione["idprenotazione"]);
?>

<h1 class="h3 mb-4"><?php echo $modifica ? "Modifica prenotazione" : "Aggiungi prenotazione"; ?></h1>

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
                    <?php echo ($prenotazione["campo"] == $c["idcampo"]) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($c["nomecampo"]); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 col-md-3">
        <label for="dataprenotazione" class="form-label">Data</label>
        <input type="date" class="form-control" id="dataprenotazione" name="dataprenotazione"
               required value="<?php echo htmlspecialchars($prenotazione["dataprenotazione"]); ?>" />
    </div>

    <div class="col-12 col-md-3">
        <label for="orainizio" class="form-label">Ora inizio</label>
        <input type="time" class="form-control" id="orainizio" name="orainizio"
               required value="<?php echo substr($prenotazione["orainizio"], 0, 5); ?>" />
    </div>

    <div class="col-12 col-md-3">
        <label for="orafine" class="form-label">Ora fine</label>
        <input type="time" class="form-control" id="orafine" name="orafine"
               required value="<?php echo substr($prenotazione["orafine"], 0, 5); ?>" />
    </div>

    <div class="col-12 col-md-3">
        <label for="numpartecipanti" class="form-label">Partecipanti</label>
        <input type="number" class="form-control" id="numpartecipanti" name="numpartecipanti"
               min="1" required value="<?php echo htmlspecialchars($prenotazione["numpartecipanti"]); ?>" />
    </div>

    <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Salva</button>
        <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>admin/gestione-prenotazioni.php">Annulla</a>
    </div>
</form>
