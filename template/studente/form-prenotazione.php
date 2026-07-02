<?php /* VISTA: form prenotazione studente — da implementare.
   Modello: template/admin/form-prenotazione.php, ma:
   - NIENTE menù "Studente" (prenota per sé; l'utente lo mette il processa dalla sessione)
   - il "Campo" è GIÀ deciso: mostralo come testo e tienilo come <select id="campo"> con UNA
     sola opzione (con data-capienza) -> così js/form-prenotazione.js funziona senza modifiche
   - Data, Orario (menù AJAX da api/disponibilita.php), Partecipanti come nell'admin
   - invia in POST a studente/processa-prenotazione.php */ ?>
<h1 class="h3">Prenota un campo</h1>
