# Campi Sportivi del Campus

Applicazione web per **prenotare i campi sportivi del campus universitario**. Gli studenti sfogliano i campi disponibili e prenotano fasce orarie da un'ora; un amministratore gestisce i campi e le prenotazioni.

## Tecnologie
- **Lato server:** PHP + MySQL/MariaDB
- **Lato client:** Bootstrap 5.3, JavaScript vanilla (AJAX con `fetch`)
- **Ambiente:** XAMPP (Apache + MySQL)

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

## Architettura
1. **Front Controller** — ogni `.php` in `admin/` e `studente/` è un controller: prepara i dati e richiama il layout.
2. **Layout unico** (`template/base.php`) — navbar, sidebar (menù diverso per ruolo) e footer scritti una volta sola; cambia solo il `<main>`.
3. **`DatabaseHelper`** (`db/database.php`) — unico punto che parla col database; tutte le query con *prepared statement*.
4. **Contratto `$templateParams`** — il controller riempie l'array (`titolo`, `nome` della vista, dati, `js`) e fa `require template/base.php`, che inietta la vista nel `<main>`.
5. **Percorsi assoluti** (in `bootstrap.php`): `BASE_URL` per i link web, `__DIR__` per gli include su disco — perché le pagine stanno in sottocartelle.

## Struttura del progetto
```
campisportivi/
├── index.php               # LOGIN (porta d'ingresso) + gestione POST del login
├── registrazione.php       # registrazione studente
├── logout.php
├── bootstrap.php           # avvio: sessione, costanti, crea $dbh
│
├── studente/               # controller area studente
│   ├── home.php   campi.php   campo.php
│   ├── gestisci-prenotazione.php   processa-prenotazione.php
│   └── le-mie-prenotazioni.php   profilo.php
│
├── admin/                  # controller area admin
│   ├── home.php
│   ├── gestione-campi.php   gestisci-campo.php   processa-campo.php
│   ├── gestione-prenotazioni.php   gestisci-prenotazione.php   processa-prenotazione.php
│   └── gestione-utenti.php
│
├── api/                    # endpoint JSON (AJAX)
│   ├── disponibilita.php       # orari liberi di un campo in una data
│   ├── stato-campo.php         # apri/chiudi campo
│   ├── stato-prenotazione.php  # annulla una prenotazione (admin)
│   └── elimina-utente.php
│
├── db/
│   ├── database.php        # classe DatabaseHelper (TUTTE le query)
│   ├── creazione_db.sql    # schema (4 tabelle)
│   └── inserisci_dati.sql  # dati di prova
├── utils/functions.php     # isUserLoggedIn, isAdmin, registerLoggedUser, uploadImage…
│
├── template/               # le viste (HTML)
│   ├── base.php
│   ├── auth/       login-form.php   registrazione-form.php
│   ├── studente/   dashboard · lista-campi · singolo-campo · form-prenotazione · lista-prenotazioni · dettaglio-profilo
│   └── admin/      dashboard · lista-campi · form-campo · lista-prenotazioni · lista-utenti · form-prenotazione
│
├── css/style.css
├── js/    gestione-campi.js · gestione-prenotazioni.js · gestione-utenti.js · form-prenotazione.js
├── upload/                 # immagini dei campi
│
├── mockup.pdf                    # mockup Balsamiq (mobile + desktop)
└── relazione-progettazione.pdf   # relazione di design (personas, scenarios, principi)
```

## Database (4 tabelle)
- **utente** — studenti + admin (campo `ruolo`); password salvate con `password_hash`
- **sport** — calcetto, basket, tennis, padel…
- **campo** — nome, sport, luogo, tipo (indoor/outdoor), capienza, orari, `aperto`, immagine
- **prenotazione** — utente + campo + data + ora inizio/fine + numero partecipanti + stato

Schema in `db/creazione_db.sql`, dati di prova in `db/inserisci_dati.sql`.

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

## Documentazione di progetto
- **`mockup.pdf`** — i mockup dell'interfaccia (versione mobile e desktop di ogni schermata)
- **`relazione-progettazione.pdf`** — la relazione di design (personas, scenarios, principi Mobile First / User Centered / Accessibile)

## Autori
Progetto per il corso di **Tecnologie Web** — Alessio Pulzoni e Gianmarco Spinaci.
