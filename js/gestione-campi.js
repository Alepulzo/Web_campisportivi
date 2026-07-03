// GESTIONE CAMPI (area admin)
// Permette di chiudere/riaprire un campo SENZA ricaricare la pagina.

document.addEventListener("DOMContentLoaded", function () {

    var forms = document.querySelectorAll(".js-stato-form");

    forms.forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // blocco l'invio normale: niente cambio pagina

            // l'input "aperto" contiene il nuovo stato
            var nuovoAperto = form.querySelector('input[name="aperto"]').value === "1";

            // conferma: chiudendo un campo si annullano le sue prenotazioni future
            var messaggio = nuovoAperto
                ? "Vuoi davvero riaprire questo campo?"
                : "Chiudendo il campo, le prenotazioni future verranno annullate. Continuare?";
            if (!confirm(messaggio)) {
                return; // l'utente ha annullato
            }

            // Mando i dati del form al server.
            fetch(form.action, {
                method: "POST",
                body: new FormData(form)
            })
            .then(function (risposta) { return risposta.json(); })
            .then(function (dati) {
                if (!dati.success) {
                    alert("Operazione non riuscita.");
                    return;
                }
                aggiornaRiga(form, dati.aperto);
            })
            .catch(function () {
                alert("Errore di rete: riprova.");
            });
        });
    });

    // aggiorna badge, bottone e valore nascosto
    function aggiornaRiga(form, aperto) {
        var card    = form.closest(".card");
        var badge   = card.querySelector(".js-stato-badge");
        var bottone = form.querySelector(".js-stato-btn");
        var input   = form.querySelector('input[name="aperto"]');

        if (aperto == 1) {
            // Il campo ora è APERTO
            badge.textContent = "Aperto";
            badge.classList.remove("text-bg-secondary");
            badge.classList.add("text-bg-success");
            bottone.textContent = "Chiudi";
            input.value = 0; // il prossimo click lo richiuderà
        } else {
            // Il campo ora è CHIUSO
            badge.textContent = "Chiuso";
            badge.classList.remove("text-bg-success");
            badge.classList.add("text-bg-secondary");
            bottone.textContent = "Riapri";
            input.value = 1; // il prossimo click lo riaprirà
        }
    }
});
