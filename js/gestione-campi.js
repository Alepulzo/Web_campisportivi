// GESTIONE CAMPI (area admin) — chiudi/riapri un campo senza ricaricare la pagina.
document.querySelectorAll(".js-stato-form").forEach(function (form) {
    form.addEventListener("submit", function (event) {
        event.preventDefault();

        // leggo i valori dal form
        const idcampo = form.querySelector('input[name="idcampo"]').value;
        const aperto  = form.querySelector('input[name="aperto"]').value;

        // conferma (aperto vale "1" se sto per aprire)
        const messaggio = aperto === "1"
            ? "Vuoi davvero riaprire questo campo?"
            : "Chiudendo il campo, le prenotazioni future verranno annullate. Continuare?";
        if (!confirm(messaggio)) return;

        cambiaStato(form, idcampo, aperto);
    });
});

// manda i dati al server e aggiorna la card
async function cambiaStato(form, idcampo, aperto) {
    const formData = new FormData();
    formData.append("idcampo", idcampo);
    formData.append("aperto", aperto);

    try {
        const response = await fetch(form.action, { method: "POST", body: formData });
        if (!response.ok) throw new Error(`Response status: ${response.status}`);
        const json = await response.json();
        if (!json.success) {
            alert("Operazione non riuscita.");
            return;
        }

        // aggiorno badge, bottone e input nascosto della card
        const badge   = form.closest(".card").querySelector(".js-stato-badge");
        const bottone = form.querySelector(".js-stato-btn");
        const input   = form.querySelector('input[name="aperto"]');

        if (json.aperto == 1) {
            // il campo ora è APERTO
            badge.textContent = "Aperto";
            badge.classList.remove("text-bg-secondary");
            badge.classList.add("text-bg-success");
            bottone.textContent = "Chiudi";
            input.value = 0; // il prossimo click lo chiuderà
        } else {
            // il campo ora è CHIUSO
            badge.textContent = "Chiuso";
            badge.classList.remove("text-bg-success");
            badge.classList.add("text-bg-secondary");
            bottone.textContent = "Riapri";
            input.value = 1; // il prossimo click lo riaprirà
        }
    } catch (error) {
        console.log(error.message);
    }
}
