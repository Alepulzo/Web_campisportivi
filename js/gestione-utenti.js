// GESTIONE UTENTI (area admin) — elimina uno studente senza ricaricare la pagina.
document.querySelectorAll(".js-elimina-form").forEach(function (form) {
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        if (!confirm("Vuoi davvero eliminare questo utente?")) return;

        const idutente = form.querySelector('input[name="idutente"]').value;
        eliminaUtente(form, idutente);
    });
});

// manda i dati al server e toglie la riga dalla tabella
async function eliminaUtente(form, idutente) {
    const formData = new FormData();
    formData.append("idutente", idutente);

    try {
        const response = await fetch(form.action, { method: "POST", body: formData });
        if (!response.ok) throw new Error(`Response status: ${response.status}`);
        const json = await response.json();
        if (!json.success) {
            alert("Operazione non riuscita.");
            return;
        }
        form.closest("tr").remove(); // tolgo la riga dalla tabella

        // annuncio per lo screen reader
        document.getElementById("annuncio").textContent = "Utente eliminato";
    } catch (error) {
        console.log(error.message);
    }
}
