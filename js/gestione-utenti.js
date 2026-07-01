// GESTIONE UTENTI (area admin)
// Elimina uno studente SENZA ricaricare la pagina.

document.addEventListener("DOMContentLoaded", function () {

    // tutti i form "elimina" della tabella
    var forms = document.querySelectorAll(".js-elimina-form");

    forms.forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // niente cambio pagina

            if (!confirm("Vuoi davvero eliminare questo utente?")) {
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
                form.closest("tr").remove(); // tolgo la riga dalla tabella
            })
            .catch(function () {
                alert("Errore di rete: riprova.");
            });
        });
    });
});
