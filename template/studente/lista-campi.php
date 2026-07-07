<?php /* Vista: elenco campi prenotabili (studente). Solo campi aperti, niente colonna Stato. */ ?>

<h1 class="h3 pb-3 mb-4 border-bottom">Prenota un campo</h1>

<?php $campi = $templateParams["campi"] ?? array(); ?>

<?php if(count($campi) === 0): ?>

    <div class="shadow-sm rounded p-4 text-center text-muted bg-white">Nessun campo disponibile al momento.</div>

<?php else: ?>

    <div class="row g-3">
        <?php foreach($campi as $campo): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="<?php echo UPLOAD_URL . htmlspecialchars($campo["imgcampo"]); ?>"
                     class="card-img-top"
                     alt="Foto del campo <?php echo htmlspecialchars($campo["nomecampo"]); ?>" />
                <div class="card-body d-flex flex-column">

                    <h2 class="h5 card-title mb-1"><?php echo htmlspecialchars($campo["nomecampo"]); ?></h2>

                    <p class="text-muted mb-2">
                        <?php echo htmlspecialchars($campo["nomesport"]); ?> · <?php echo ucfirst($campo["tipocampo"]); ?>
                    </p>
                    <p class="card-text small mb-1"><?php echo htmlspecialchars($campo["luogocampo"]); ?></p>
                    <p class="card-text small text-muted mb-3">
                        <?php echo substr($campo["orarioapertura"], 0, 5); ?>–<?php echo substr($campo["orariochiusura"], 0, 5); ?>
                    </p>

                    <div class="d-flex flex-wrap gap-1 mt-auto">
                        <a class="btn btn-sm btn-outline-secondary"
                           href="<?php echo BASE_URL; ?>studente/campo.php?id=<?php echo $campo["idcampo"]; ?>">Descrizione</a>
                        <a class="btn btn-sm btn-primary"
                           href="<?php echo BASE_URL; ?>studente/gestisci-prenotazione.php?campo=<?php echo $campo["idcampo"]; ?>">Prenota</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
