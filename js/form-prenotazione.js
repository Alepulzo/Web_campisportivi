// FORM PRENOTAZIONE
// Riempie il menù "Orario" con le fasce da 1 ora LIBERE del campo nel giorno scelto.
// Le chiede al server (api/disponibilita.php) via fetch, solo quando ci sono SIA campo SIA giorno.

document.addEventListener("DOMContentLoaded", function () {
    var selCampo  = document.getElementById("campo");
    var inputData = document.getElementById("dataprenotazione");
    var selOrario = document.getElementById("orario");
    var inputId   = document.querySelector('input[name="idprenotazione"]');
    if (!selCampo || !inputData || !selOrario) return;

    var url    = selOrario.getAttribute("data-url");
    var scelto = selOrario.getAttribute("data-selezionato") || "";   // fascia attuale (in modifica)

    var inputPartecipanti = document.getElementById("numpartecipanti");
    var capienzaHint      = document.getElementById("capienzaHint");

    function pad(n) { return (n < 10 ? "0" : "") + n; }

    function messaggio(testo) {
        selOrario.innerHTML = '<option value="">' + testo + '</option>';
    }

    // mostra "Max N partecipanti" e imposta il limite in base al campo scelto
    function aggiornaCapienza() {
        if (!inputPartecipanti) return;
        var opt = selCampo.options[selCampo.selectedIndex];
        var capienza = opt ? opt.getAttribute("data-capienza") : "";
        if (capienza) {
            inputPartecipanti.max = capienza;
            if (capienzaHint) capienzaHint.textContent = "Max " + capienza + " partecipanti";
        } else {
            inputPartecipanti.removeAttribute("max");
            if (capienzaHint) capienzaHint.textContent = "";
        }
    }

    function caricaOrari() {
        var campo = selCampo.value;
        var data  = inputData.value;

        // finché non scelgo sia campo sia giorno, non posso scegliere l'orario
        if (!campo || !data) {
            messaggio("— scegli campo e giorno —");
            return;
        }

        var escludi   = (inputId && inputId.value) ? inputId.value : "0";  // in modifica ignoro me stessa
        var richiesta = url + "?campo=" + encodeURIComponent(campo)
                            + "&data=" + encodeURIComponent(data)
                            + "&escludi=" + encodeURIComponent(escludi);

        fetch(richiesta)
            .then(function (r) { return r.json(); })
            .then(function (dati) {
                var slot = dati.slot || [];
                if (slot.length === 0) {
                    messaggio("Nessuna fascia disponibile");
                    return;
                }
                selOrario.innerHTML = "";
                var def = document.createElement("option");
                def.value = "";
                def.textContent = "— scegli —";
                selOrario.appendChild(def);

                slot.forEach(function (inizio) {
                    var h = parseInt(inizio.substring(0, 2), 10);
                    var o = document.createElement("option");
                    o.value = inizio;
                    o.textContent = inizio + "–" + pad(h + 1) + ":00";
                    if (inizio === scelto) o.selected = true;
                    selOrario.appendChild(o);
                });
            })
            .catch(function () { messaggio("Errore nel caricamento"); });
    }

    // ricarico quando cambio campo o giorno (dopo un cambio non tengo la vecchia selezione)
    selCampo.addEventListener("change",  function () { scelto = ""; aggiornaCapienza(); caricaOrari(); });
    inputData.addEventListener("change", function () { scelto = ""; caricaOrari(); });

    // all'avvio: in modifica campo e giorno sono già pieni -> carico subito
    aggiornaCapienza();
    caricaOrari();
});
