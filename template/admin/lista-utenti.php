<?php /* Vista: lista utenti (admin) — elimina solo studenti, via AJAX. */ ?>

<h1 class="h3 mb-4">Gestione utenti</h1>

<?php $utenti = isset($templateParams["utenti"]) ? $templateParams["utenti"] : array(); ?>

<?php if(count($utenti) === 0): ?>

    <div class="shadow-sm rounded p-4 text-center text-muted bg-white">Nessun utente.</div>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <caption class="visually-hidden">Elenco degli utenti</caption>
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Cognome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ruolo</th>
                    <th scope="col">Registrato il</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($utenti as $u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u["nome"]); ?></td>
                    <td><?php echo htmlspecialchars($u["cognome"]); ?></td>
                    <td><?php echo htmlspecialchars($u["email"]); ?></td>
                    <td>
                        <?php if($u["ruolo"] === "admin"): ?>
                            <span class="badge text-bg-dark">Admin</span>
                        <?php else: ?>
                            <span class="badge text-bg-info">Studente</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date("d/m/Y", strtotime($u["dataregistrazione"])); ?></td>
                    <td>
                        <?php if($u["ruolo"] === "studente"): ?>
                        <!-- Elimina: gestito via JavaScript (js/gestione-utenti.js), senza ricaricare -->
                        <form method="post" action="<?php echo BASE_URL; ?>api/elimina-utente.php" class="d-inline js-elimina-form">
                            <input type="hidden" name="idutente" value="<?php echo $u["idutente"]; ?>" />
                            <button type="submit" class="btn btn-sm btn-outline-danger">Elimina</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>
