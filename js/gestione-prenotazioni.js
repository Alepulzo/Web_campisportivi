// GESTIONE PRENOTAZIONI (area admin) — annulla una prenotazione senza ricaricare la pagina.
document.querySelectorAll(".js-annulla-form").forEach(function (form) {
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        if (!confirm("Vuoi davvero annullare questa prenotazione?")) return;

        const idprenotazione = form.querySelector('input[name="idprenotazione"]').value;
        annullaPrenotazione(form, idprenotazione);
    });
});

// manda i dati al server e aggiorna la riga
async function annullaPrenotazione(form, idprenotazione) {
    const formData = new FormData();
    formData.append("idprenotazione", idprenotazione);

    try {
        const response = await fetch(form.action, { method: "POST", body: formData });
        if (!response.ok) throw new Error(`Response status: ${response.status}`);
        const json = await response.json();
        if (!json.success) {
            alert("Operazione non riuscita.");
            return;
        }

        // badge -> Cancellata e tolgo i bottoni
        const riga = form.closest("tr");
        const badge = riga.querySelector(".js-stato-badge");
        badge.textContent = "Cancellata";
        badge.classList.remove("text-bg-success");
        badge.classList.add("text-bg-secondary");
        const azioni = riga.querySelector(".js-azioni");
        if (azioni) azioni.remove();

        // annuncio per lo screen reader
        document.getElementById("annuncio").textContent = "Prenotazione annullata";
    } catch (error) {
        console.log(error.message);
    }
}
