<?php /* VISTA: form di registrazione (dentro <main>). */ ?>
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h4 text-center pb-3 mb-4 border-bottom">Registrati</h1>

                <?php if(isset($templateParams["errore"])): ?>
                <div class="alert alert-danger" role="alert"><?php echo $templateParams["errore"]; ?></div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>registrazione.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cognome" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="cognome" name="cognome" required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email istituzionale</label>
                        <input type="email" class="form-control" id="email" name="email" autocomplete="email" placeholder="nome.cognome@studio.unibo.it" aria-describedby="emailHelp" required />
                        <div id="emailHelp" class="form-text">Usa la tua email istituzionale.</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="conferma" class="form-label">Conferma password</label>
                            <input type="password" class="form-control" id="conferma" name="conferma" autocomplete="new-password" required />
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Crea account</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Hai già un account? <a href="<?php echo BASE_URL; ?>index.php">Accedi</a>
                </p>
            </div>
        </div>
    </div>
</div>
