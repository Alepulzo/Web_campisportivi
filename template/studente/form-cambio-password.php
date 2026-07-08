<?php /* Vista: pagina cambio password. */ ?>
<h1 class="h3 pb-3 mb-4 border-bottom">Cambia password</h1>

<div class="card shadow-sm border-0" style="max-width: 1200px;">
<div class="card-body">
    <?php if(isset($templateParams["errore_password"])): ?>
    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($templateParams["errore_password"]); ?></div>
    <?php endif; ?>
    <?php if(isset($templateParams["successo_password"])): ?>
    <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($templateParams["successo_password"]); ?></div>
    <?php endif; ?>

    <form method="post" action="<?php echo BASE_URL; ?>studente/cambio-password.php">
        <div class="mb-3">
            <label for="vecchia_password" class="form-label">Password attuale</label>
            <input type="password" class="form-control" id="vecchia_password" name="vecchia_password" autocomplete="current-password" required />
        </div>
        <div class="mb-3">
            <label for="nuova_password" class="form-label">Nuova password</label>
            <input type="password" class="form-control" id="nuova_password" name="nuova_password" autocomplete="new-password" required />
        </div>
        <div class="mb-3">
            <label for="conferma_password" class="form-label">Conferma nuova password</label>
            <input type="password" class="form-control" id="conferma_password" name="conferma_password" autocomplete="new-password" required />
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-dark">Aggiorna password</button>
            <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>studente/profilo.php">Annulla</a>
        </div>
    </form>
</div>
</div>
