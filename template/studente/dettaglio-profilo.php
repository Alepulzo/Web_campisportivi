<?php
/* Vista: profilo studente — dati personali (sola lettura). */
$utente = $templateParams["utente"] ?? array();
?>
<h1 class="h3 pb-3 mb-4 border-bottom">Il mio profilo</h1>

<div class="card shadow-sm border-0" style="max-width: 800px;">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Nome</dt>
            <dd class="col-sm-9"><?php echo htmlspecialchars($utente["nome"]); ?></dd>

            <dt class="col-sm-3">Cognome</dt>
            <dd class="col-sm-9"><?php echo htmlspecialchars($utente["cognome"]); ?></dd>

            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9"><?php echo htmlspecialchars($utente["email"]); ?></dd>

            <dt class="col-sm-3">Iscritto dal</dt>
            <dd class="col-sm-9 mb-0"><?php echo date("d/m/Y", strtotime($utente["dataregistrazione"])); ?></dd>
        </dl>
    </div>
</div>

<a class="btn btn-dark mt-4" href="<?php echo BASE_URL; ?>studente/cambio-password.php">Cambia password</a>
