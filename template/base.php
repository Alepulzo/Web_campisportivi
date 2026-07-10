<?php

?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo isset($templateParams["titolo"]) ? $templateParams["titolo"] : "Campi Sportivi del Campus"; ?></title>

    <!-- Bootstrap 5.3.3 da CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css" />
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Link "salta al contenuto" per l'accessibilità da tastiera -->
    <a href="#contenuto" class="visually-hidden-focusable position-absolute top-0 start-0 m-2 btn btn-light">Salta al contenuto</a>

    <!-- Zona annunci: il JS ci scrive l'esito delle azioni AJAX, lo screen reader lo legge -->
    <div id="annuncio" class="visually-hidden" aria-live="polite"></div>

    <!-- BARRA SUPERIORE: marchio + (su mobile) bottone per aprire il menu -->
    <header class="navbar navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand mb-0">Campi Sportivi del Campus</span>
            <?php if(isUserLoggedIn()): ?>
            <button class="navbar-toggler d-md-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="false" aria-label="Apri o chiudi il menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <?php endif; ?>
        </div>
    </header>

    <div class="container-fluid flex-grow-1 d-flex flex-column">
        <div class="row flex-grow-1">

            <?php if(isUserLoggedIn()): ?>
            <!-- SIDEBAR -->
            <nav id="sidebarMenu" class="col-12 col-md-3 col-lg-2 d-md-flex flex-column bg-white border-end shadow-sm collapse p-3"
                 aria-label="Menu principale">

                <!-- blocco utente: nome -->
                <div class="mb-3 pb-3 border-bottom">
                    <div class="fw-semibold"><?php echo htmlspecialchars($_SESSION['nome'] . ' ' . $_SESSION['cognome']); ?></div>
                </div>

                <ul class="nav nav-pills flex-column gap-1">
                    <?php if(isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link <?php isActive('home.php'); ?>" href="<?php echo BASE_URL; ?>admin/home.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('gestione-campi.php'); ?>" href="<?php echo BASE_URL; ?>admin/gestione-campi.php">Gestione campi</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('gestione-prenotazioni.php'); ?>" href="<?php echo BASE_URL; ?>admin/gestione-prenotazioni.php">Gestione prenotazioni</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('gestione-utenti.php'); ?>" href="<?php echo BASE_URL; ?>admin/gestione-utenti.php">Gestione utenti</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link <?php isActive('home.php'); ?>" href="<?php echo BASE_URL; ?>studente/home.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('campi.php'); ?>" href="<?php echo BASE_URL; ?>studente/campi.php">Prenota un campo</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('le-mie-prenotazioni.php'); ?>" href="<?php echo BASE_URL; ?>studente/le-mie-prenotazioni.php">Le mie prenotazioni</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('profilo.php'); ?>" href="<?php echo BASE_URL; ?>studente/profilo.php">Profilo</a></li>
                    <?php endif; ?>
                </ul>

                <a class="btn btn-outline-danger btn-sm mt-4 mt-md-auto" href="<?php echo BASE_URL; ?>logout.php">Logout</a>
            </nav>
            <?php endif; ?>

            <!-- CONTENUTO PRINCIPALE: qui viene iniettata la vista -->
            <main id="contenuto" class="<?php echo isUserLoggedIn() ? 'col-12 col-md-9 col-lg-10' : 'col-12'; ?> p-4">
                <?php
                if(isset($templateParams["nome"])){
                    require __DIR__ . "/" . $templateParams["nome"];   // es. template/studente/lista-campi.php
                }
                ?>
            </main>

        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="m-0 small">Campi Sportivi del Campus</p>
    </footer>

    <!-- Bootstrap JS (serve per aprire/chiudere il menu su mobile) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Eventuali script JS della pagina -->
    <?php
    if(isset($templateParams["js"])):
        foreach($templateParams["js"] as $script):
    ?>
        <script src="<?php echo BASE_URL . $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</body>
</html>
