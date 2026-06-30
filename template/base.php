<?php
/* ============================================================
 * base.php  —  IL LAYOUT comune a TUTTE le pagine
 * ------------------------------------------------------------
 * I link/risorse usano BASE_URL (perché le pagine stanno in sottocartelle);
 * la vista specifica viene inclusa con __DIR__ (percorso assoluto).
 * ============================================================ */
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

    <!-- INTESTAZIONE -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <h1 class="h4 m-0">🏟️ Campi Sportivi del Campus</h1>
        </div>
    </header>

    <!-- MENU: appare solo se sei loggato, e mostra voci diverse per studente/admin -->
    <?php if(isUserLoggedIn()): ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-secondary" aria-label="Menu principale">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                aria-controls="menu" aria-expanded="false" aria-label="Apri o chiudi il menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav me-auto">
                    <?php if(isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link <?php isActive('home.php'); ?>" href="<?php echo BASE_URL; ?>admin/home.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('gestione-campi.php'); ?>" href="<?php echo BASE_URL; ?>admin/gestione-campi.php">Gestione campi</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('gestione-prenotazioni.php'); ?>" href="<?php echo BASE_URL; ?>admin/gestione-prenotazioni.php">Prenotazioni</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('gestione-utenti.php'); ?>" href="<?php echo BASE_URL; ?>admin/gestione-utenti.php">Utenti</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link <?php isActive('home.php'); ?>" href="<?php echo BASE_URL; ?>studente/home.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('campi.php'); ?>" href="<?php echo BASE_URL; ?>studente/campi.php">Prenota un campo</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('le-mie-prenotazioni.php'); ?>" href="<?php echo BASE_URL; ?>studente/le-mie-prenotazioni.php">Le mie prenotazioni</a></li>
                        <li class="nav-item"><a class="nav-link <?php isActive('profilo.php'); ?>" href="<?php echo BASE_URL; ?>studente/profilo.php">Profilo</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item d-flex align-items-center me-md-3 text-white-50">Ciao, <?php echo htmlspecialchars($_SESSION['nome']); ?></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- CONTENUTO: qui viene iniettata la VISTA specifica della pagina -->
    <main id="contenuto" class="container my-4 flex-grow-1">
        <?php
        if(isset($templateParams["nome"])){
            require __DIR__ . "/" . $templateParams["nome"];   // es. template/studente/lista-campi.php
        }
        ?>
    </main>

    <!-- PIÈ DI PAGINA -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="m-0 small">Campi Sportivi del Campus — Tecnologie Web A.A. 2025/2026</p>
        </div>
    </footer>

    <!-- Bootstrap JS (serve per il menu a tendina su mobile) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Eventuali script JS della pagina (li useremo per l'AJAX nella Fase 5) -->
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
