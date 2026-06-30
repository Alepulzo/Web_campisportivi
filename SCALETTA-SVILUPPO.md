# Scaletta di sviluppo — Campi Sportivi

Lista ordinata degli step per costruire il progetto, dal primo all'ultimo.
Spuntiamo le caselle `[ ]` man mano che completiamo.

## Come usare questa scaletta (4 principi)
1. **Un pezzo alla volta**, dall'alto verso il basso.
2. **Prima leggere, poi scrivere**: prima le pagine che *mostrano* dati, poi quelle che *salvano*.
3. **DatabaseHelper a richiesta**: aggiungiamo i metodi del `DatabaseHelper` solo quando servono (non tutti subito).
4. **Testare dopo ogni FASE** prima di passare alla successiva (così gli errori si trovano subito).

> A fianco di ogni fase, tra parentesi, i **punti della griglia d'esame** che copre.

---

## ✅ FASE 0 — Preparazione
- [ ] Importare in phpMyAdmin **prima** `db/creazione_db.sql` (schema) **poi** `db/inserisci_dati.sql` (dati)
- [ ] Mettere le immagini dei campi in `upload/` (beachvolley.jpg, padel.jpg, calcetto.jpg, tennis.jpg, basket.jpg, pallavolo.jpg)
- [ ] Avviare Apache + MySQL e aprire `http://localhost/campisportivi/` (anche se è bianco va bene)

---

## 🔧 FASE 1 — Ossatura (le fondamenta)
*Obiettivo: far funzionare il "motore" (avvio, database, layout). Ancora niente di visibile, ma è la base di tutto.*
- [ ] `bootstrap.php` — verificare che ci sia (sessione, BASE_URL, `$dbh`) e che il nome DB sia `campisportivi`
- [ ] `db/database.php` — scrivere il **costruttore** di `DatabaseHelper` (connessione `mysqli` + controllo errore)
- [ ] `utils/functions.php` — `isUserLoggedIn()`, `isStudente()`, `isAdmin()`, `registerLoggedUser()`, `logout()`, `isActive()`
- [ ] `template/base.php` — il layout con Bootstrap: intestazione, **menu diverso per ruolo**, `<main>` con la vista, footer (usando `BASE_URL`)
- [ ] `css/style.css` — i ritocchi di stile sopra Bootstrap
- ✅ **Test:** aprendo una pagina si vede la cornice (intestazione/menu/footer) senza errori.

---

## 🔑 FASE 2 — Login, Logout, Registrazione (il cancello) — *(Registrazione e login: 4 pt)*
*Obiettivo: entrare nel sito ed essere mandati nell'area giusta in base al ruolo.*
- [ ] `DatabaseHelper`: `checkLogin($email,$password)`, `getUserByEmail($email)`, `registerUser(...)`
- [ ] `template/auth/login-form.php` — il form di login (email + password)
- [ ] `index.php` — gestisce il login: controlla credenziali → salva in sessione → redirect (admin → `admin/home.php`, studente → `studente/home.php`); se già loggato, redirect diretto
- [ ] `logout.php` — `logout()` + ritorno al login
- [ ] `template/auth/registrazione-form.php` — il form di registrazione
- [ ] `registrazione.php` — valida i dati (password uguali, email libera) → crea l'utente → login automatico
- ✅ **Test:** login come admin e come studente (redirect giusto); registrazione di un nuovo studente; logout.

---

## 🎓 FASE 3 — Area STUDENTE (la fruizione del servizio) — *(Fruizione: 8 pt · Profilo: 4 pt)*
*Obiettivo: lo studente sfoglia i campi, prenota, gestisce le sue prenotazioni, vede profilo e dashboard.*
*Ordine: prima la LETTURA (sfogliare), poi la SCRITTURA (prenotare/annullare).*

**3.1 — Sfogliare i campi (lettura)**
- [ ] `DatabaseHelper`: `getSport()`, `getCampi()`, `getCampiBySport($id)`
- [ ] `studente/campi.php` + `template/studente/lista-campi.php` — lista dei campi (con filtro per sport)

**3.2 — Dettaglio campo (lettura)**
- [ ] `DatabaseHelper`: `getCampoById($id)`
- [ ] `studente/campo.php` + `template/studente/singolo-campo.php` — dettaglio del campo + **form di prenotazione** (data, ora)

**3.3 — Prenotare (scrittura)**
- [ ] `DatabaseHelper`: `isSlotLibero($campo,$data,$inizio,$fine)`, `insertPrenotazione(...)`
- [ ] `studente/processa-prenotazione.php` — salva la prenotazione (controlla: slot libero, dentro gli orari, capienza) → redirect

**3.4 — Le mie prenotazioni + annulla (lettura + scrittura)**
- [ ] `DatabaseHelper`: `getPrenotazioniByUser($id)`, `annullaPrenotazione($idpren,$idutente)`
- [ ] `studente/le-mie-prenotazioni.php` + `template/studente/lista-prenotazioni.php` — lista + bottone **Annulla** (con conferma `confirm()`)
- [ ] aggiungere a `processa-prenotazione.php` l'azione "annulla"

**3.5 — Dashboard studente**
- [ ] `DatabaseHelper`: `getProssimePrenotazioni($id,$n)`, `countPrenotazioniByUser($id)`
- [ ] `studente/home.php` + `template/studente/home.php` — benvenuto + prossime prenotazioni + conteggi

**3.6 — Profilo**
- [ ] `DatabaseHelper`: `getUserById($id)`
- [ ] `studente/profilo.php` + `template/studente/profilo.php` — i dati personali
- ✅ **Test:** come studente → sfoglia, prenota, vedi/annulla, controlla dashboard e profilo.

---

## 🛠️ FASE 4 — Area ADMIN (gestione del servizio) — *(CRUD Admin: 8 pt — la più importante!)*
*Obiettivo: l'admin gestisce campi, prenotazioni e utenti.*

**4.1 — Dashboard admin**
- [ ] `DatabaseHelper`: `countCampi()`, `countPrenotazioni()`, `countUtenti()`
- [ ] `admin/home.php` + `template/admin/dashboard.php` — statistiche + scorciatoie alle 3 gestioni

**4.2 — Gestione campi (lista)**
- [ ] `admin/gestione-campi.php` + `template/admin/gestione-campi.php` — lista campi con i bottoni (Modifica/Cancella/Chiudi-Riapri/Aggiungi)

**4.3 — CRUD campo (il cuore)**
- [ ] `utils/functions.php`: `getEmptyCampo()`, `getAction($n)`, `uploadImage(...)`
- [ ] `DatabaseHelper`: `insertCampo(...)`, `updateCampo(...)`, `deleteCampo($id)`, `setStatoCampo($id,$aperto)`
- [ ] `admin/gestisci-campo.php` + `template/admin/form-campo.php` — il form unico (azione 1=inserisci, 2=modifica, 3=cancella)
- [ ] `admin/processa-campo.php` — esegue insert/update/delete + **upload immagine** + chiudi/riapri → redirect

**4.4 — Gestione prenotazioni**
- [ ] `DatabaseHelper`: `getAllPrenotazioni()`, `annullaPrenotazioneAdmin($id)`
- [ ] `admin/gestione-prenotazioni.php` + `template/admin/gestione-prenotazioni.php` — tutte le prenotazioni + Annulla
- [ ] `admin/processa-prenotazione.php` — annulla (lato admin) → redirect

**4.5 — Gestione utenti**
- [ ] `DatabaseHelper`: `getAllUtenti()`, `deleteUtente($id)`
- [ ] `admin/gestione-utenti.php` + `template/admin/gestione-utenti.php` — lista utenti + Elimina
- [ ] `admin/processa-utente.php` — elimina → redirect
- ✅ **Test:** come admin → dashboard, CRUD campi (aggiungi/modifica/elimina/chiudi), vedi/annulla prenotazioni, vedi/elimina utenti.

---

## ✨ FASE 5 — Effetto WOW (AJAX: orari liberi) — *(Effetto WOW: 4 pt)*
*Obiettivo: scegliendo la data nella prenotazione, compaiono gli orari liberi senza ricaricare.*
- [ ] `DatabaseHelper`: `getPrenotazioniByCampoEData($campo,$data)`
- [ ] `api/disponibilita.php` — ritorna in **JSON** gli orari liberi del campo per quella data
- [ ] `js/prenotazione.js` — al cambio di data chiama l'api e mostra gli slot liberi
- [ ] collegare il tutto in `studente/campo.php` (caricare il js)
- ✅ **Test:** scegli un campo e una data → compaiono gli orari liberi al volo.

---

## 🎨 FASE 6 — Rifinitura, accessibilità e sicurezza — *(Design: 4 pt)*
*Obiettivo: sito curato, responsive, accessibile e sicuro.*
- [ ] **Responsive**: provare su mobile e desktop (griglia Bootstrap, mobile-first)
- [ ] **Accessibilità**: tag semantici, `label` sui form, `alt` sulle immagini, focus visibile, skip-link, tabelle con `th`
- [ ] **Messaggi**: errori (login/registrazione/prenotazione), conferme, stati vuoti ("nessuna prenotazione")
- [ ] **Validazioni lato server** su tutti i form (campi obbligatori, slot libero, capienza, orari)
- [ ] **Sicurezza**: `isAdmin()` su ogni pagina admin, login su ogni pagina studente; prepared statement ovunque; `htmlspecialchars` sugli input
- ✅ **Test:** provare tutti i flussi e anche le cose "vietate" (entrare in admin da studente, prenotare uno slot occupato, ecc.).

---

## 📦 FASE 7 — Consegna
*Obiettivo: preparare consegna e discussione.*
- [ ] **Relazione di progettazione** (1 pagina): Personas, Scenari, mockup mobile+desktop (la fase di design richiesta dalle specifiche)
- [ ] **README** finale + istruzioni di avvio aggiornate
- [ ] Test finale su un **import pulito** del database
- [ ] Condividere repository/cartella col prof (qualche giorno **prima** della discussione)

---

### Riepilogo punti d'esame coperti (32 totali)
| Fase | Cosa | Punti |
|---|---|---|
| 2 | Registrazione e login | 4 |
| 3 | Fruizione del servizio + Profilo | 8 + 4 |
| 4 | CRUD lato Admin | 8 |
| 5 | Effetto WOW | 4 |
| 6 | Design | 4 |

> 👉 Stiamo qui: **FASE 0 / inizio FASE 1**. Il prossimo passo è scrivere `bootstrap.php` + il costruttore del `DatabaseHelper`.
