<?php
/* Vista: dettaglio di un singolo campo (studente). */
$campo = $templateParams["campo"] ?? array();
?>

<h1 class="h3 pb-3 mb-4 border-bottom"><?php echo htmlspecialchars($campo["nomecampo"]); ?></h1>

<div class="row g-4">
    <div class="col-12 col-md-5">
        <img src="<?php echo UPLOAD_URL . htmlspecialchars($campo["imgcampo"]); ?>"
             alt="Foto del campo <?php echo htmlspecialchars($campo["nomecampo"]); ?>"
             class="img-fluid rounded" />
    </div>
    <div class="col-12 col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Sport</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($campo["nomesport"]); ?></dd>

                    <dt class="col-sm-4">Luogo</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($campo["luogocampo"]); ?></dd>

                    <dt class="col-sm-4">Tipo</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars(ucfirst($campo["tipocampo"])); ?></dd>

                    <dt class="col-sm-4">Orario</dt>
                    <dd class="col-sm-8"><?php echo substr($campo["orarioapertura"], 0, 5); ?>–<?php echo substr($campo["orariochiusura"], 0, 5); ?></dd>

                    <dt class="col-sm-4">Capienza massima</dt>
                    <dd class="col-sm-8 mb-0"><?php echo htmlspecialchars($campo["capienzamax"]); ?> persone</dd>
                </dl>

                <?php if(!empty($campo["descrizionecampo"])): ?>
                    <p class="mt-3 mb-0"><?php echo nl2br(htmlspecialchars($campo["descrizionecampo"])); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <a class="btn btn-dark mt-4" href="<?php echo BASE_URL; ?>studente/gestisci-prenotazione.php?campo=<?php echo $campo["idcampo"]; ?>">Prenota</a>
    </div>
</div>
