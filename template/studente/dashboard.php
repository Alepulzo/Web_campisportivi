<?php
/* Vista: dashboard studente (benvenuto + prossime prenotazioni + conteggio). */
$prossime        = $templateParams["prossime"] ?? [];
$numPrenotazioni = $templateParams["numPrenotazioni"] ?? 0;
?>

<h1 class="h3 mb-1">Ciao, <?php echo htmlspecialchars($_SESSION["nome"]); ?>!</h1>
<p class="text-muted pb-3 mb-4 border-bottom">Ecco un riepilogo delle tue prenotazioni.</p>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card text-center h-100 shadow-sm border-0">
            <div class="card-body">
                <p class="display-6 mb-0"><?php echo $numPrenotazioni; ?></p>
                <p class="text-muted mb-0">Prenotazioni confermate</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card text-center h-100 shadow-sm border-0">
            <div class="card-body">
                <p class="display-6 mb-0"><?php echo count($prossime); ?></p>
                <p class="text-muted mb-0">Prossime prenotazioni</p>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h2 class="h5 mb-0">Prossime prenotazioni</h2>
    <a class="btn btn-dark btn-sm" href="<?php echo BASE_URL; ?>studente/campi.php">Prenota un campo</a>
</div>

<?php if(count($prossime) === 0): ?>

    <p class="text-muted">Nessuna prenotazione in programma.</p>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <caption class="visually-hidden">Prossime prenotazioni</caption>
            <thead class="table-secondary">
                <tr>
                    <th scope="col">Data</th>
                    <th scope="col">Orario</th>
                    <th scope="col">Campo</th>
                    <th scope="col">Partecipanti</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($prossime as $p): ?>
                <tr>
                    <td><?php echo date("d/m/Y", strtotime($p["dataprenotazione"])); ?></td>
                    <td><?php echo substr($p["orainizio"], 0, 5); ?>–<?php echo substr($p["orafine"], 0, 5); ?></td>
                    <td><?php echo htmlspecialchars($p["nomecampo"]); ?></td>
                    <td><?php echo htmlspecialchars($p["numpartecipanti"]); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>
