<?php
/* Vista: profilo studente — dati personali (sola lettura). */
$utente = $templateParams["utente"] ?? array();
?>
<h1 class="h3 pb-3 mb-4 border-bottom">Il mio profilo</h1>

<dl class="row">
    <dt class="col-sm-3">Nome</dt>
    <dd class="col-sm-9"><?php echo htmlspecialchars($utente["nome"]); ?></dd>

    <dt class="col-sm-3">Cognome</dt>
    <dd class="col-sm-9"><?php echo htmlspecialchars($utente["cognome"]); ?></dd>

    <dt class="col-sm-3">Email</dt>
    <dd class="col-sm-9"><?php echo htmlspecialchars($utente["email"]); ?></dd>

    <dt class="col-sm-3">Iscritto dal</dt>
    <dd class="col-sm-9"><?php echo date("d/m/Y", strtotime($utente["dataregistrazione"])); ?></dd>
</dl>

<a class="btn btn-dark" href="<?php echo BASE_URL; ?>studente/cambio-password.php">Cambia password</a>
