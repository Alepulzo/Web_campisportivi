<?php /* Vista: elenco campi prenotabili (studente). Solo campi aperti, niente colonna Stato. */ ?>

<h1 class="h3 mb-4">Prenota un campo</h1>

<?php $campi = isset($templateParams["campi"]) ? $templateParams["campi"] : array(); ?>

<?php if(count($campi) === 0): ?>

    <p class="text-muted">Nessun campo disponibile al momento.</p>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <caption class="visually-hidden">Elenco dei campi prenotabili</caption>
            <thead>
                <tr>
                    <th scope="col">Foto</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Sport</th>
                    <th scope="col">Luogo</th>
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
                        <div class="d-flex flex-wrap gap-1">
                            <a class="btn btn-sm btn-outline-secondary"
                               href="<?php echo BASE_URL; ?>studente/campo.php?id=<?php echo $campo["idcampo"]; ?>">Descrizione</a>
                            <a class="btn btn-sm btn-primary"
                               href="<?php echo BASE_URL; ?>studente/gestisci-prenotazione.php?campo=<?php echo $campo["idcampo"]; ?>">Prenota</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>
