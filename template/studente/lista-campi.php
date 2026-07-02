<?php /* VISTA: elenco campi da prenotare — da implementare.
   Come template/admin/lista-campi.php ma:
   - solo campi APERTI: nel controller usa $dbh->getCampi(true)  (aggiungere il parametro $soloAperti)
   - niente colonna "Stato" (sono tutti aperti)
   - colonna Azioni con DUE bottoni:
       * "Prenota"     -> gestisci-prenotazione.php?campo=X  (va dritto al form)
       * "Descrizione" -> campo.php?id=X                      (pagina di dettaglio) */ ?>
<h1 class="h3">Prenota un campo</h1>
