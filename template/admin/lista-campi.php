<?php /* Vista: lista dei campi (admin). */ ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h1 class="h3 mb-0">Gestione campi</h1>
    <a class="btn btn-primary" href="<?php echo BASE_URL; ?>admin/gestisci-campo.php">Aggiungi campo</a>
</div>

<?php $campi = isset($templateParams["campi"]) ? $templateParams["campi"] : array(); ?>

<?php if(count($campi) === 0): ?>

    <p class="text-muted">Nessun campo presente. Usa "Aggiungi campo" per crearne uno.</p>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <caption class="visually-hidden">Elenco dei campi sportivi</caption>
            <thead>
                <tr>
                    <th scope="col">Foto</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Sport</th>
                    <th scope="col">Luogo</th>
                    <th scope="col">Stato</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($campi as $campo): ?>
                <tr>
                    <td>
                        <img src="<?php echo UPLOAD_URL . htmlspecialchars($campo["imgcampo"]); ?>"
                             alt="Foto del campo <?php echo htmlspecialchars($campo["nomecampo"]); ?>"
                             width="64" height="48" class="rounded object-fit-cover" />
                    </td>
                    <td><?php echo htmlspecialchars($campo["nomecampo"]); ?></td>
                    <td><?php echo htmlspecialchars($campo["nomesport"]); ?></td>
                    <td><?php echo htmlspecialchars($campo["luogocampo"]); ?></td>
                    <td>
                        <span class="js-stato-badge badge <?php echo $campo["aperto"] ? "text-bg-success" : "text-bg-secondary"; ?>">
                            <?php echo $campo["aperto"] ? "Aperto" : "Chiuso"; ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
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
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>
