<?php
/* Vista: dashboard admin (numeri + prenotazioni di oggi). */
$numCampi         = $templateParams["numCampi"];
$numCampiChiusi   = $templateParams["numCampiChiusi"];
$prenotazioniOggi = $templateParams["prenotazioniOggi"];
?>

<h1 class="h3 mb-4">Dashboard</h1>

<!-- Riquadri con i numeri -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card text-center h-100 shadow-sm border-0">
            <div class="card-body">
                <p class="display-6 mb-0"><?php echo $numCampi; ?></p>
                <p class="text-muted mb-0">Campi</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card text-center h-100 shadow-sm border-0">
            <div class="card-body">
                <p class="display-6 mb-0"><?php echo $numCampiChiusi; ?></p>
                <p class="text-muted mb-0">Campi chiusi</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card text-center h-100 shadow-sm border-0">
            <div class="card-body">
                <p class="display-6 mb-0"><?php echo count($prenotazioniOggi); ?></p>
                <p class="text-muted mb-0">Prenotazioni di oggi</p>
            </div>
        </div>
    </div>
</div>

<!-- Prenotazioni di oggi -->
<h2 class="h5 mb-3">Prenotazioni di oggi</h2>

<?php if(count($prenotazioniOggi) === 0): ?>

    <div class="shadow-sm rounded p-4 text-center text-muted bg-white">Nessuna prenotazione per oggi.</div>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <caption class="visually-hidden">Prenotazioni di oggi</caption>
            <thead>
                <tr>
                    <th scope="col">Orario</th>
                    <th scope="col">Campo</th>
                    <th scope="col">Studente</th>
                    <th scope="col">Partecipanti</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($prenotazioniOggi as $p): ?>
                <tr>
                    <td><?php echo substr($p["orainizio"], 0, 5); ?>–<?php echo substr($p["orafine"], 0, 5); ?></td>
                    <td><?php echo htmlspecialchars($p["nomecampo"]); ?></td>
                    <td><?php echo htmlspecialchars($p["nome"] . " " . $p["cognome"]); ?></td>
                    <td><?php echo htmlspecialchars($p["numpartecipanti"]); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>
