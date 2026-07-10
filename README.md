# Campi Sportivi del Campus

Applicazione web per **prenotare i campi sportivi del campus universitario**. Gli studenti sfogliano i campi disponibili e prenotano fasce orarie da un'ora; un amministratore gestisce i campi e le prenotazioni.

## Funzionalità

**Area studente** (registrazione con email `@studio.unibo.it`)
- Dashboard con riepilogo e prossime prenotazioni
- Sfoglia i campi aperti e prenota (data + fascia oraria da 1 ora)
- Gli **orari liberi si caricano in AJAX** in base al campo e al giorno scelti
- "Le mie prenotazioni": modifica o annulla
- Profilo personale

**Area admin**
- Dashboard con statistiche rapide e prenotazioni del giorno
- Gestione campi — **CRUD completo** (aggiungi / modifica / elimina, chiudi-riapri, con foto)
- Gestione prenotazioni (vede tutte, annulla)
- Gestione utenti (elenco, elimina studenti)
- Le azioni rapide (chiudi campo, annulla, elimina) avvengono **in AJAX, senza ricaricare** la pagina

**Design:** Mobile First, User Centered, Accessibile (skip-link, focus visibile, attributi ARIA, buon contrasto).

## Installazione e avvio (XAMPP)

> ⚠️ **Importante:** il sito usa `BASE_URL = "/campisportivi/"`, quindi la cartella **deve chiamarsi `campisportivi`**. Se la cloni con un altro nome (es. `Web_campisportivi`), i link, il CSS e le immagini si rompono.

1. **Clona** dentro `htdocs`, forzando il nome della cartella:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/Alepulzo/Web_campisportivi.git campisportivi
   ```
   *(il `campisportivi` finale forza il nome giusto; senza, git creerebbe `Web_campisportivi`. In alternativa, cambia `BASE_URL` in `bootstrap.php` col nome della tua cartella.)*
2. Avvia **Apache** e **MySQL** dal pannello di XAMPP.
3. In **phpMyAdmin** → **Importa**: prima `db/creazione_db.sql` (lo schema), poi `db/inserisci_dati.sql` (i dati di prova).
4. Apri **`http://localhost/campisportivi/`** → si apre la pagina di login.

## Credenziali di prova
| Ruolo | Email | Password |
|---|---|---|
| Admin | `marco.verdi@unibo.it` | `admin123` |
| Studente | `gino.pino@studio.unibo.it` | `password` |

## Autori
Progetto per il corso di **Tecnologie Web** — Alessio Pulzoni e Gianmarco Spinaci.
