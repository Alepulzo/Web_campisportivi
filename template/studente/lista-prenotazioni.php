<?php
/* Vista: le mie prenotazioni (studente) — oggi, future, passate (a tendina). */
$sezioni = [
    ["titolo" => "Prenotazioni di oggi", "lista" => $templateParams["oggi"]    ?? array(), "azioni" => true,  "collassabile" => false],
    ["titolo" => "Prenotazioni future",  "lista" => $templateParams["future"]  ?? array(), "azioni" => true,  "collassabile" => false],
    ["titolo" => "Prenotazioni passate", "lista" => $templateParams["passate"] ?? array(), "azioni" => false, "collassabile" => true],
];
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h1 class="h3 mb-0">Le mie prenotazioni</h1>
    <a class="btn btn-primary" href="<?php echo BASE_URL; ?>studente/campi.php">Prenota un campo</a>
</div>

<?php foreach($sezioni as $i => $sez): ?>

    <?php if($sez["collassabile"]): ?>
        <h2 class="h5 mb-3">
            <button class="btn btn-outline-secondary btn-sm" type="button"
                    data-bs-toggle="collapse" data-bs-target="#sezione<?php echo $i; ?>"
                    aria-expanded="false" aria-controls="sezione<?php echo $i; ?>">
                <?php echo $sez["titolo"]; ?> (<?php echo count($sez["lista"]); ?>)
            </button>
        </h2>
    <?php else: ?>
        <h2 class="h5 mb-3"><?php echo $sez["titolo"]; ?></h2>
    <?php endif; ?>

    <div id="sezione<?php echo $i; ?>"<?php echo $sez["collassabile"] ? ' class="collapse"' : ''; ?>>

        <?php if(count($sez["lista"]) === 0): ?>

            <p class="text-muted mb-4">Nessuna prenotazione.</p>

        <?php else: ?>

            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle">
                    <caption class="visually-hidden"><?php echo $sez["titolo"]; ?></caption>
                    <thead>
                        <tr>
                            <th scope="col">Data</th>
                            <th scope="col">Orario</th>
                            <th scope="col">Campo</th>
                            <th scope="col">Partecipanti</th>
                            <th scope="col">Stato</th>
                            <?php if($sez["azioni"]): ?><th scope="col">Azioni</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sez["lista"] as $p): ?>
                        <tr>
                            <td><?php echo date("d/m/Y", strtotime($p["dataprenotazione"])); ?></td>
                            <td><?php echo substr($p["orainizio"], 0, 5); ?>–<?php echo substr($p["orafine"], 0, 5); ?></td>
                            <td><?php echo htmlspecialchars($p["nomecampo"]); ?></td>
                            <td><?php echo htmlspecialchars($p["numpartecipanti"]); ?></td>
                            <td>
                                <span class="badge <?php echo $p["stato"] === "confermata" ? "text-bg-success" : "text-bg-secondary"; ?>">
                                    <?php echo $p["stato"] === "confermata" ? "Confermata" : "Cancellata"; ?>
                                </span>
                            </td>
                            <?php if($sez["azioni"]): ?>
                            <td>
                                <?php if($p["stato"] === "confermata"): ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="<?php echo BASE_URL; ?>studente/gestisci-prenotazione.php?id=<?php echo $p["idprenotazione"]; ?>">Modifica</a>

                                    <form method="post" action="<?php echo BASE_URL; ?>studente/processa-prenotazione.php" class="d-inline"
                                          onsubmit="return confirm('Vuoi davvero annullare questa prenotazione?');">
                                        <input type="hidden" name="azione" value="annulla" />
                                        <input type="hidden" name="idprenotazione" value="<?php echo $p["idprenotazione"]; ?>" />
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Annulla</button>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </div>

<?php endforeach; ?>
