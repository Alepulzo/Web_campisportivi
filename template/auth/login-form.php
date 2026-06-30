<?php /* VISTA: form di login (dentro <main>). */ ?>
<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 text-center mb-4">Accedi</h1>

                <?php if(isset($templateParams["errorelogin"])): ?>
                <div class="alert alert-danger" role="alert"><?php echo $templateParams["errorelogin"]; ?></div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>index.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" autocomplete="email" required />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Accedi</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Non hai un account? <a href="<?php echo BASE_URL; ?>registrazione.php">Registrati</a>
                </p>
            </div>
        </div>
    </div>
</div>
