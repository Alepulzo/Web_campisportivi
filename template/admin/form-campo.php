<?php
/* Vista: form per aggiungere o modificare un campo (stesso form per i due casi). */
$campo    = $templateParams["campo"];
$sport    = $templateParams["sport"];
$modifica = !empty($campo["idcampo"]);
?>

<h1 class="h3 mb-4"><?php echo $modifica ? "Modifica campo" : "Aggiungi campo"; ?></h1>

<form method="post" action="<?php echo BASE_URL; ?>admin/processa-campo.php"
      enctype="multipart/form-data" class="row g-3">

    <!-- idcampo nascosto: vuoto = nuovo, pieno = modifica -->
    <input type="hidden" name="idcampo" value="<?php echo htmlspecialchars($campo["idcampo"]); ?>" />

    <div class="col-12">
        <label for="nomecampo" class="form-label">Nome del campo</label>
        <input type="text" class="form-control" id="nomecampo" name="nomecampo"
               maxlength="100" required
               value="<?php echo htmlspecialchars($campo["nomecampo"]); ?>" />
    </div>

    <div class="col-12 col-md-6">
        <label for="sport" class="form-label">Sport</label>
        <select class="form-select" id="sport" name="sport" required>
            <option value="">— scegli —</option>
            <?php foreach($sport as $s): ?>
                <option value="<?php echo $s["idsport"]; ?>"
                    <?php echo ($campo["sport"] == $s["idsport"]) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($s["nomesport"]); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 col-md-6">
        <label for="tipocampo" class="form-label">Tipo</label>
        <select class="form-select" id="tipocampo" name="tipocampo" required>
            <option value="">— scegli —</option>
            <option value="indoor"  <?php echo ($campo["tipocampo"] === "indoor")  ? "selected" : ""; ?>>Indoor (al coperto)</option>
            <option value="outdoor" <?php echo ($campo["tipocampo"] === "outdoor") ? "selected" : ""; ?>>Outdoor (all'aperto)</option>
        </select>
    </div>

    <div class="col-12 col-md-8">
        <label for="luogocampo" class="form-label">Luogo</label>
        <input type="text" class="form-control" id="luogocampo" name="luogocampo"
               maxlength="100" required
               value="<?php echo htmlspecialchars($campo["luogocampo"]); ?>" />
    </div>

    <div class="col-12 col-md-4">
        <label for="capienzamax" class="form-label">Capienza massima</label>
        <input type="number" class="form-control" id="capienzamax" name="capienzamax"
               min="1" required
               value="<?php echo htmlspecialchars($campo["capienzamax"]); ?>" />
    </div>

    <div class="col-12 col-md-6">
        <label for="orarioapertura" class="form-label">Orario di apertura</label>
        <input type="time" class="form-control" id="orarioapertura" name="orarioapertura"
               required value="<?php echo htmlspecialchars($campo["orarioapertura"]); ?>" />
    </div>

    <div class="col-12 col-md-6">
        <label for="orariochiusura" class="form-label">Orario di chiusura</label>
        <input type="time" class="form-control" id="orariochiusura" name="orariochiusura"
               required value="<?php echo htmlspecialchars($campo["orariochiusura"]); ?>" />
    </div>

    <div class="col-12">
        <label for="descrizionecampo" class="form-label">Descrizione</label>
        <textarea class="form-control" id="descrizionecampo" name="descrizionecampo" rows="3"><?php echo htmlspecialchars($campo["descrizionecampo"] ?? ""); ?></textarea>
    </div>

    <div class="col-12">
        <label for="imgcampo" class="form-label">Foto del campo</label>
        <?php if($modifica && !empty($campo["imgcampo"])): ?>
            <div class="mb-2">
                <img src="<?php echo UPLOAD_URL . htmlspecialchars($campo["imgcampo"]); ?>"
                     alt="Foto attuale del campo" width="120" height="80" class="rounded object-fit-cover" />
            </div>
        <?php endif; ?>
        <input type="file" class="form-control" id="imgcampo" name="imgcampo"
               accept="image/*" <?php echo $modifica ? "" : "required"; ?> />
        <?php if($modifica): ?>
            <div class="form-text">Lascia vuoto per mantenere la foto attuale.</div>
        <?php endif; ?>
    </div>

    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="aperto" name="aperto" value="1"
                   <?php echo $campo["aperto"] ? "checked" : ""; ?> />
            <label class="form-check-label" for="aperto">Campo aperto (prenotabile)</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Salva</button>
        <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>admin/gestione-campi.php">Annulla</a>
    </div>
</form>
