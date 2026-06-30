# Prenotazione Campi Sportivi — Struttura & Architettura

Web app per prenotare i campi sportivi del campus. Fa **le stesse cose** del progetto
precedente, ma usando **solo tecniche viste in laboratorio** (niente roba troppo complessa).
Per ora c'è solo lo **scheletro** (cartelle + file con commenti): nessuna logica scritta.

---

## 1. Cosa deve fare il sito (login-first)

Si entra dal **login** e in base al ruolo si finisce nell'area giusta:

```
   index.php (LOGIN) ──► studente ─► studente/home.php
                     └─► admin    ─► admin/home.php
```

**Area STUDENTE**
- **Dashboard** (riepilogo + prossime prenotazioni)
- **Prenota un campo** (sfoglia i campi → scegli data/ora → prenota)
- **Le mie prenotazioni** (vedi e annulla)
- **Profilo**

**Area ADMIN**
- **Dashboard** (statistiche rapide)
- **Gestione campi** (CRUD: aggiungi / modifica / elimina / chiudi-riapri)
- **Gestione prenotazioni** (vede tutte, può annullare)
- **Gestione utenti** (elenco utenti, elimina)


Tecnologie: **PHP** lato server, **JavaScript** + **Bootstrap** lato client.

---

## 2. L'architettura (il pattern dei lab del prof)

1. **Front Controller** — ogni `.php` è un "controllore": prepara i dati e chiama il layout.
2. **Layout unico (`template/base.php`)** — header/menu/footer una volta sola; cambia solo `<main>`.
3. **Un solo punto per il DB (`db/database.php`, `DatabaseHelper`)** — tutte le query, con *prepared statement*.

**Contratto `$templateParams`:** il controller riempie l'array (`titolo`, `nome` della vista, dati)
e fa `require .../template/base.php`, che inietta la vista in `<main>`.

### Percorsi (`BASE_URL`)
Le pagine stanno in sottocartelle (`studente/`, `admin/`), quindi in `bootstrap.php`:
- **`BASE_URL`** (`/campisportivi/`) davanti a link, css, js, immagini;
- **`UPLOAD_PATH`** (disco) e **`UPLOAD_URL`** (web) per le immagini dei campi;
- le pagine includono l'avvio con `require __DIR__ . '/../bootstrap.php'`.

---

## 3. Struttura completa

```
campisportivi/
├── index.php                 # LOGIN (porta d'ingresso)
├── registrazione.php         # registrazione studente
├── logout.php
├── bootstrap.php             # avvio: sessione, BASE_URL, costanti, crea $dbh
│
├── studente/                 # ── AREA STUDENTE ──
│   ├── home.php              #   dashboard
│   ├── campi.php             #   sfoglia i campi (filtro per sport)
│   ├── campo.php             #   dettaglio campo + form di prenotazione (?id=)
│   ├── le-mie-prenotazioni.php
│   ├── profilo.php
│   └── processa-prenotazione.php   # prenota/annulla (POST→redirect)
│
├── admin/                    # ── AREA ADMIN ──
│   ├── home.php              #   dashboard (statistiche)
│   ├── gestione-campi.php    #   elenco campi + CRUD
│   ├── gestisci-campo.php   #   form aggiungi/modifica/elimina campo
│   ├── processa-campo.php   #   esegue il CRUD + upload (POST→redirect)
│   ├── gestione-prenotazioni.php    # tutte le prenotazioni
│   ├── processa-prenotazione.php    # annulla una prenotazione (POST→redirect)
│   ├── gestione-utenti.php  #   elenco utenti
│   └── processa-utente.php  #   elimina un utente (POST→redirect)
│
├── api/
│   └── disponibilita.php     # AJAX (JSON): orari liberi di un campo in una data (effetto WOW)
│
├── db/
│   ├── database.php          # classe DatabaseHelper (TUTTE le query)
│   └── creazione_db.sql      # schema (4 tabelle)
├── utils/
│   └── functions.php         # isActive, isUserLoggedIn, isStudente, isAdmin, uploadImage…
│
├── template/                 # ── LE VISTE ──
│   ├── base.php              #   layout comune
│   ├── auth/
│   │   ├── login-form.php
│   │   └── registrazione-form.php
│   ├── studente/
│   │   ├── home.php
│   │   ├── lista-campi.php
│   │   ├── singolo-campo.php
│   │   ├── lista-prenotazioni.php
│   │   └── profilo.php
│   └── admin/
│       ├── dashboard.php
│       ├── gestione-campi.php
│       ├── form-campo.php
│       ├── gestione-prenotazioni.php
│       └── gestione-utenti.php
│
├── css/  style.css
├── js/   prenotazione.js      # AJAX: chiede gli orari liberi e li mostra
└── upload/                    # immagini dei campi
```

### Quale pagina usa quale vista
| Controller | Vista (`$templateParams["nome"]`) |
|---|---|
| `index.php` | `auth/login-form.php` |
| `registrazione.php` | `auth/registrazione-form.php` |
| `studente/home.php` | `studente/home.php` |
| `studente/campi.php` | `studente/lista-campi.php` |
| `studente/campo.php` | `studente/singolo-campo.php` |
| `studente/le-mie-prenotazioni.php` | `studente/lista-prenotazioni.php` |
| `studente/profilo.php` | `studente/profilo.php` |
| `admin/home.php` | `admin/dashboard.php` |
| `admin/gestione-campi.php` | `admin/gestione-campi.php` |
| `admin/gestisci-campo.php` | `admin/form-campo.php` |
| `admin/gestione-prenotazioni.php` | `admin/gestione-prenotazioni.php` |
| `admin/gestione-utenti.php` | `admin/gestione-utenti.php` |

---

## 4. Il database (4 tabelle semplici)

- **utente** (studenti + admin, campo `ruolo`)
- **sport** (calcetto, basket, tennis, padel…)
- **campo** (nome, sport, luogo, tipo indoor/outdoor, capienza, orari, `aperto`, immagine)
- **prenotazione** (utente + campo + data + ora_inizio/ora_fine + num_partecipanti + stato)

Relazioni: uno **sport** → tanti **campi**; un **utente** → tante **prenotazioni**; un **campo** →
tante **prenotazioni**. La tabella `prenotazione` collega utente e campo con data/ora (cuore del servizio).
Schema completo in `db/creazione_db.sql`.

### Mappatura con i lab "Blog"
| Lab (Blog) | Progetto |
|---|---|
| `articolo` | `campo` |
| `categoria` | `sport` |
| `autore` | `utente` (con `ruolo`) |
| `articolo_ha_categoria` | `prenotazione` (utente↔campo) |

---

## 5. Piano di lavoro
1. **Database** — eseguire `db/creazione_db.sql` + dati di prova.
2. **Ossatura** — `bootstrap.php`, `DatabaseHelper`, `template/base.php`, `css/`.
3. **Login + registrazione** — sessioni, ruoli, redirect per area.
4. **Area studente** — dashboard, sfoglia/prenota campi, le mie prenotazioni, profilo.
5. **Area admin** — dashboard, gestione campi (CRUD), gestione prenotazioni, gestione utenti.
6. **Effetto WOW** — orari liberi in AJAX al momento della prenotazione.
7. **Rifinitura** — responsive, accessibilità, test.

### Divisione compiti (gruppo da 2)
- **Persona A — server/dati:** schema DB, `DatabaseHelper`, area admin (CRUD), logica prenotazioni.
- **Persona B — vista/client:** `base.php` + template, Bootstrap, area studente, JavaScript/AJAX.

---

## 6. Avvio (XAMPP)

### Se scarichi il progetto da GitHub
⚠️ **La cartella deve chiamarsi `campisportivi`** (il sito usa `BASE_URL = "/campisportivi/"`).
Quindi clona indicando quel nome di cartella:
```bash
cd /c/xampp/htdocs
git clone https://github.com/Alepulzo/Web_campisportivi.git campisportivi
```
(il `campisportivi` finale forza il nome giusto; senza, git creerebbe la cartella `Web_campisportivi`
e link/css/immagini si romperebbero. In alternativa, cambia `BASE_URL` in `bootstrap.php`.)

### Mettere in funzione
1. La cartella deve trovarsi in `C:\xampp\htdocs\campisportivi`.
2. Avviare **Apache** + **MySQL** da XAMPP.
3. In phpMyAdmin → **Importa**: prima `db/creazione_db.sql` (lo schema), poi `db/inserisci_dati.sql` (i dati).
4. Aprire `http://localhost/campisportivi/` (si apre il login).

**Utenti di prova** → admin: `marco.verdi@unibo.it` / `admin123` · studente: `gino.pino@studio.unibo.it` / `password`
