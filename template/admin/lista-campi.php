<?php /* Vista: lista dei campi (admin) — a card. */ ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h1 class="h3 mb-0">Gestione campi</h1>
    <a class="btn btn-dark" href="<?php echo BASE_URL; ?>admin/gestisci-campo.php">Aggiungi campo</a>
</div>

<?php $campi = isset($templateParams["campi"]) ? $templateParams["campi"] : array(); ?>

<?php if(count($campi) === 0): ?>

    <div class="shadow-sm rounded p-4 text-center text-muted bg-white">Nessun campo presente. Usa "Aggiungi campo" per crearne uno.</div>

<?php else: ?>

    <div class="row g-3">
        <?php foreach($campi as $campo): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="<?php echo UPLOAD_URL . htmlspecialchars($campo["imgcampo"]); ?>"
                     class="card-img-top"
                     alt="Foto del campo <?php echo htmlspecialchars($campo["nomecampo"]); ?>" />
                <div class="card-body d-flex flex-column">

                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <h2 class="h5 card-title mb-0"><?php echo htmlspecialchars($campo["nomecampo"]); ?></h2>
                        <span class="js-stato-badge badge <?php echo $campo["aperto"] ? "text-bg-success" : "text-bg-secondary"; ?>">
                            <?php echo $campo["aperto"] ? "Aperto" : "Chiuso"; ?>
                        </span>
                    </div>

                    <p class="text-muted mb-2">
                        <?php echo htmlspecialchars($campo["nomesport"]); ?> · <?php echo ucfirst($campo["tipocampo"]); ?>
                    </p>
                    <p class="card-text small mb-1"><?php echo htmlspecialchars($campo["luogocampo"]); ?></p>
                    <p class="card-text small text-muted mb-3">
                        <?php echo substr($campo["orarioapertura"], 0, 5); ?>–<?php echo substr($campo["orariochiusura"], 0, 5); ?>
                    </p>

                    <!-- azioni in fondo alla card -->
                    <div class="d-flex flex-wrap gap-1 mt-auto">
                        <a class="btn btn-sm btn-outline-primary"
                           href="<?php echo BASE_URL; ?>admin/gestisci-campo.php?id=<?php echo $campo["idcampo"]; ?>">Modifica</a>

                        <!-- Chiudi / Riapri: gestito via JavaScript (js/gestione-campi.js) -->
                        <form method="post" action="<?php echo BASE_URL; ?>api/stato-campo.php" class="d-inline js-stato-form">
                            <input type="hidden" name="idcampo" value="<?php echo $campo["idcampo"]; ?>" />
                            <input type="hidden" name="aperto" value="<?php echo $campo["aperto"] ? 0 : 1; ?>" />
                            <button type="submit" class="btn btn-sm btn-outline-secondary js-stato-btn">
                                <?php echo $campo["aperto"] ? "Chiudi" : "Riapri"; ?>
                            </button>
                        </form>

                        <!-- Cancella: chiede conferma prima di inviare -->
                        <form method="post" action="<?php echo BASE_URL; ?>admin/processa-campo.php" class="d-inline"
                              onsubmit="return confirm('Vuoi davvero eliminare questo campo?');">
                            <input type="hidden" name="azione" value="elimina" />
                            <input type="hidden" name="idcampo" value="<?php echo $campo["idcampo"]; ?>" />
                            <button type="submit" class="btn btn-sm btn-outline-danger">Cancella</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
