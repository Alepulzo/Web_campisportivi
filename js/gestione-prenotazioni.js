// GESTIONE PRENOTAZIONI (area admin)
// Annulla una prenotazione SENZA ricaricare la pagina (come chiudi/riapri dei campi).

document.addEventListener("DOMContentLoaded", function () {

    // tutti i form "annulla" della tabella
    var forms = document.querySelectorAll(".js-annulla-form");

    forms.forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // niente cambio pagina

            if (!confirm("Vuoi davvero annullare questa prenotazione?")) {
                return; // l'utente ha annullato
            }

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
                aggiornaRiga(form);
            })
            .catch(function () {
                alert("Errore di rete: riprova.");
            });
        });
    });

    // Dopo l'annullamento: badge -> "Cancellata" e tolgo i bottoni.
    function aggiornaRiga(form) {
        var riga   = form.closest("tr");
        var badge  = riga.querySelector(".js-stato-badge");
        var azioni = riga.querySelector(".js-azioni");

        badge.textContent = "Cancellata";
        badge.classList.remove("text-bg-success");
        badge.classList.add("text-bg-secondary");

        if (azioni) {
            azioni.remove(); // una prenotazione annullata non ha più azioni
        }
    }
});
