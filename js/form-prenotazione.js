// FORM PRENOTAZIONE
// Riempie il menù "Orario" con le fasce da 1 ora LIBERE del campo nel giorno scelto.
// Le chiede al server (api/disponibilita.php) via fetch, solo quando ci sono SIA campo SIA giorno.

const selCampo  = document.getElementById("campo");
const inputData = document.getElementById("dataprenotazione");
const selOrario = document.getElementById("orario");
const inputId   = document.querySelector('input[name="idprenotazione"]');
const inputPartecipanti = document.getElementById("numpartecipanti");
const capienzaHint      = document.getElementById("capienzaHint");

const url  = selOrario.getAttribute("data-url");
let scelto = selOrario.getAttribute("data-selezionato") || "";   // fascia attuale (in modifica)

function pad(n) { return (n < 10 ? "0" : "") + n; }

function messaggio(testo) {
    selOrario.innerHTML = '<option value="">' + testo + '</option>';
}

// mostra "Max N partecipanti" e imposta il limite in base al campo scelto
function aggiornaCapienza() {
    if (!inputPartecipanti) return;
    const opt = selCampo.options[selCampo.selectedIndex];
    const capienza = opt ? opt.getAttribute("data-capienza") : "";
    if (capienza) {
        inputPartecipanti.max = capienza;
        if (capienzaHint) capienzaHint.textContent = "Max " + capienza + " partecipanti";
    } else {
        inputPartecipanti.removeAttribute("max");
        if (capienzaHint) capienzaHint.textContent = "";
    }
}

// costruisce le <option> come stringa HTML
function generaOrari(slot) {
    let html = '<option value="">— scegli —</option>';
    for (let i = 0; i < slot.length; i++) {
        const inizio = slot[i];
        const h = parseInt(inizio.substring(0, 2), 10);
        const selected = (inizio === scelto) ? " selected" : "";
        html += `<option value="${inizio}"${selected}>${inizio}–${pad(h + 1)}:00</option>`;
    }
    return html;
}

// chiede al server gli orari liberi
async function caricaOrari() {
    const campo = selCampo.value;
    const data  = inputData.value;

    // finché non scelgo sia campo sia giorno, non posso scegliere l'orario
    // (data-msg-vuoto permette di personalizzare il messaggio quando il campo è già fisso, es. studente)
    if (!campo || !data) {
        messaggio(selOrario.getAttribute("data-msg-vuoto") || "— scegli campo e giorno —");
        return;
    }

    const escludi   = (inputId && inputId.value) ? inputId.value : "0";  // in modifica ignoro me stessa
    const richiesta = url + "?campo=" + encodeURIComponent(campo)
                        + "&data=" + encodeURIComponent(data)
                        + "&escludi=" + encodeURIComponent(escludi);

    try {
        const response = await fetch(richiesta);
        if (!response.ok) throw new Error(`Response status: ${response.status}`);
        const json = await response.json();

        const slot = json.slot || [];
        if (slot.length === 0) {
            messaggio("Nessuna fascia disponibile");
            return;
        }
        selOrario.innerHTML = generaOrari(slot);
    } catch (error) {
        messaggio("Errore nel caricamento");
    }
}

// ricarico quando cambio campo o giorno (dopo un cambio non tengo la vecchia selezione)
selCampo.addEventListener("change",  function () { scelto = ""; aggiornaCapienza(); caricaOrari(); });
inputData.addEventListener("change", function () { scelto = ""; caricaOrari(); });

// all'avvio: in modifica campo e giorno sono già pieni -> carico subito
aggiornaCapienza();
caricaOrari();
