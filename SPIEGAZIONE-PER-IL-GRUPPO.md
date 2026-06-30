# Come funziona il sito — Guida per il gruppo

---

## 0. L'idea in due righe

È un sito per **prenotare i campi sportivi del campus**. Non c'è una parte pubblica: si entra dal
**login**. In base a chi sei, finisci in una delle due aree:
- **Studente** → prenota i campi e gestisce le sue prenotazioni.
- **Admin** → gestisce i campi, le prenotazioni e gli utenti.

```
   LOGIN  ─►  sei studente?  ─►  area STUDENTE
          └►  sei admin?     ─►  area ADMIN
```

---

## 1. I 6 concetti base (da capire PRIMA di tutto)

Se capisci questi 6 punti, capisci tutto il sito.

### 1) Ogni pagina del sito è un file `.php`
Quando nel browser apri un indirizzo (es. `.../studente/campi.php`), il server esegue quel file e
ti manda indietro la pagina. Quindi **un file = una pagina = un indirizzo**.

### 2) Ci sono DUE tipi di file
- **File che MOSTRANO una schermata** (es. la lista dei campi, un form…). Questi disegnano qualcosa.
- **File che FANNO un'azione e basta**, i `processa-*.php`. Questi **non mostrano niente**: ricevono
  i dati di un form, li salvano nel database e poi ti **rimandano** automaticamente a un'altra pagina.

> Esempio: `gestione-campi.php` ti **mostra** la lista. `processa-campo.php` **salva** un campo e
> ti rimanda alla lista, senza farti vedere nessuna sua schermata.

### 3) Come si costruisce una schermata (il meccanismo del "template")
Tutte le pagine che mostrano qualcosa funzionano sempre nello stesso modo, in 3 passi:

1. Il file della pagina (lo chiamiamo **controllore**) chiede i dati al database e li mette in una
   "scatola" chiamata **`$templateParams`** (es: il titolo, l'elenco dei campi…).
2. Poi richiama **`template/base.php`**, che è la **cornice fissa** del sito (intestazione, menu,
   piè di pagina): si scrive **una volta sola** e vale per tutte le pagine.
3. Dentro la cornice, `base.php` inserisce la **vista** giusta, cioè il pezzo di contenuto che
   cambia da pagina a pagina (es. la lista dei campi, il form…). Le viste stanno nella cartella
   `template/`.

```
controllore (es. campi.php)
   │ prende i dati dal database e li mette in $templateParams
   ▼
template/base.php  (la cornice: intestazione + menu + ... + piè di pagina)
   │ nel centro inserisce la VISTA indicata
   ▼
template/studente/lista-campi.php  (il contenuto vero: le card dei campi)
```

In pratica: **il controllore prepara i dati, base.php fa la cornice, la vista mostra il contenuto.**

### 4) Il giro "salva e torna indietro"
Quando invii un form (es. crei una prenotazione), succede sempre questo:
```
form  ─►  processa-*.php  ─►  salva nel database  ─►  ti rimanda a una pagina normale
```
Si fa così per un motivo pratico: dopo aver salvato, ti spostiamo su un'altra pagina, così se
**ricarichi** (premi F5) **non rinvii** il form un'altra volta (niente prenotazioni doppie).

### 5) Lista → Dettaglio
Spesso c'è una pagina con il **plurale** (la lista di tante cose) e una con il **singolare** (una
cosa sola, con i dettagli):
- `campi.php` = la **lista** di tutti i campi.
- `campo.php?id=5` = **un solo** campo (il numero 5), con tutte le info.

Il `?id=5` nell'indirizzo serve a dire "fammi vedere il campo numero 5".

### 6) Niente "pannelli sopra": si cambia schermata
Quando clicchi un bottone, **si carica una pagina nuova** (cambia l'indirizzo in alto). **Non**
si apre un riquadro sopra la pagina (quei riquadri si chiamano "modali" e li abbiamo evitati apposta, erano la parte troppo complicata del vecchio progetto).
L'unica cosa che "compare" è il messaggio di conferma **"Sei sicuro?"** (Cancella/Annulla/Chiudi):
è il messaggino standard del browser con **OK / Annulla**, e dopo l'OK comunque cambia pagina.

---

## 2. AREA STUDENTE

### Cosa può fare lo studente
- Vedere una **dashboard** (riepilogo).
- **Prenotare un campo**.
- Vedere e **annullare le sue prenotazioni**.
- Vedere il suo **profilo**.

### I file dell'area studente (cartella `studente/`)
| File | Cosa fa | Vista che usa |
|---|---|---|
| `home.php` | la **dashboard**: benvenuto + prossime prenotazioni | `template/studente/home.php` |
| `campi.php` | la **lista di tutti i campi** (le card) | `template/studente/lista-campi.php` |
| `campo.php` | il **dettaglio di un campo** + il **form per prenotare** | `template/studente/singolo-campo.php` |
| `le-mie-prenotazioni.php` | la **lista delle tue prenotazioni** (con il bottone Annulla) | `template/studente/lista-prenotazioni.php` |
| `profilo.php` | i tuoi **dati personali** | `template/studente/profilo.php` |
| `processa-prenotazione.php` | **azione**: crea o annulla una prenotazione, poi ti rimanda indietro | *(nessuna: fa e rimanda)* |

### Flusso 1 — Prenotare un campo
```
studente/campi.php           ← lista di tutti i campi
   │ clicchi "Prenota" su un campo (link → campo.php?id=5)
   ▼
studente/campo.php?id=5      ← info del campo + FORM (scegli data e ora)
   │ compili e premi "Prenota"
   ▼
studente/processa-prenotazione.php   ← salva la prenotazione → ti rimanda a...
   ▼
studente/le-mie-prenotazioni.php     ← e vedi subito la tua nuova prenotazione
```

### Flusso 2 — Annullare una prenotazione
```
studente/le-mie-prenotazioni.php   ← la lista delle TUE prenotazioni
   │ clicchi "Annulla" sulla riga
   │ esce il messaggio:  "Sei sicuro di annullare?"  → premi OK
   ▼
studente/processa-prenotazione.php   ← la annulla → ti rimanda indietro
   ▼
studente/le-mie-prenotazioni.php     ← la prenotazione ora risulta annullata
```

> Nota: `processa-prenotazione.php` fa **due cose** a seconda di un'informazione nascosta nel form
> chiamata `action`: se vale `"prenota"` crea la prenotazione, se vale `"annulla"` la annulla.
> Annullare non cancella la riga dal database, ma le mette lo stato `"cancellata"` (così resta lo storico).

---

## 3. AREA ADMIN

### Cosa può fare l'admin
- Vedere una **dashboard** con qualche statistica.
- **Gestire i campi** (aggiungere, modificare, eliminare, chiudere/riaprire).
- **Gestire le prenotazioni** (vederle tutte e annullarle).
- **Gestire gli utenti** (vederli ed eliminarli).

### I file dell'area admin (cartella `admin/`)
| File | Cosa fa | Vista che usa |
|---|---|---|
| `home.php` | la **dashboard** (statistiche + scorciatoie) | `template/admin/dashboard.php` |
| `gestione-campi.php` | la **lista dei campi** con i bottoni di gestione | `template/admin/gestione-campi.php` |
| `gestisci-campo.php` | il **form** per aggiungere/modificare/cancellare un campo | `template/admin/form-campo.php` |
| `processa-campo.php` | **azione**: salva il campo (crea/modifica/elimina), poi rimanda | *(nessuna)* |
| `gestione-prenotazioni.php` | la **lista di tutte le prenotazioni** (con Annulla) | `template/admin/gestione-prenotazioni.php` |
| `processa-prenotazione.php` | **azione**: annulla una prenotazione, poi rimanda | *(nessuna)* |
| `gestione-utenti.php` | la **lista degli utenti** (con Elimina) | `template/admin/gestione-utenti.php` |
| `processa-utente.php` | **azione**: elimina un utente, poi rimanda | *(nessuna)* |

### Flusso principale — Gestione campi
Nella lista, ogni campo ha dei **bottoni**. Ecco cosa fa ciascuno.

```
admin/gestione-campi.php   (lista di tutti i campi)
┌──────────────┬──────────┬────────┬──────────────────────────────────┐
│ Campo        │ Sport    │ Stato  │ Azioni                           │
├──────────────┼──────────┼────────┼──────────────────────────────────┤
│ Calcetto A   │ Calcetto │ Aperto │ [Modifica] [Chiudi]  [Cancella]  │
│ Padel 1      │ Padel    │ Chiuso │ [Modifica] [Riapri]  [Cancella]  │
└──────────────┴──────────┴────────┴──────────────────────────────────┘
                           [ + Aggiungi campo ]
```

- **Aggiungi campo** → apre la pagina `gestisci-campo.php?action=1` → **form vuoto** → compili →
  "Salva" → `processa-campo.php` (crea) → torni alla lista.
- **Modifica** → apre la pagina `gestisci-campo.php?action=2&id=5` → **form già compilato** coi dati
  del campo → cambi → "Salva" → `processa-campo.php` (modifica) → torni alla lista.
- **Cancella** → esce il messaggio **"Sei sicuro di eliminare?"** → OK → `processa-campo.php`
  (elimina) → torni alla lista.
- **Chiudi / Riapri** → esce il messaggio **"Sei sicuro?"** → OK → `processa-campo.php` cambia lo
  stato del campo (aperto/chiuso) → torni alla lista.

### Il trucco: UN solo file per Aggiungi, Modifica e Cancella
Aggiungere e modificare un campo usano lo **stesso form** (stessi campi: nome, sport, luogo, orari…).
Per non scriverlo due volte, c'è **un solo file**, `gestisci-campo.php`, che cambia comportamento in
base a un numero nell'indirizzo, chiamato `action`:

| Indirizzo | `action` | Cosa mostra |
|---|---|---|
| `gestisci-campo.php?action=1` | 1 | form **vuoto** (Aggiungi) |
| `gestisci-campo.php?action=2&id=5` | 2 | form **compilato** (Modifica) |
| `gestisci-campo.php?action=3&id=5` | 3 | conferma (Cancella) |

E poi **un solo** `processa-campo.php` che, sempre in base a `action`, fa creare/modificare/eliminare.
(È esattamente come faceva il prof nel laboratorio 6 con `gestisci-articoli.php`.)

### Gestione prenotazioni e utenti
Più semplici: sono **liste con un bottone per riga**.
- `gestione-prenotazioni.php`: lista di tutte le prenotazioni; ogni riga ha **Annulla** (messaggio di
  conferma → `processa-prenotazione.php`).
- `gestione-utenti.php`: lista di tutti gli utenti; ogni riga ha **Elimina** (messaggio di conferma →
  `processa-utente.php`).

Qui **non** serve aprire una pagina di dettaglio: i dati stanno già nella riga e l'azione è un bottone.

---

## 4. I file "comuni" (fuori dalle due aree)

| File / cartella | A cosa serve |
|---|---|
| `index.php` | la pagina di **login** (la porta d'ingresso del sito) |
| `registrazione.php` | per **registrare** un nuovo studente |
| `logout.php` | per **uscire** |
| `bootstrap.php` | si avvia all'inizio di ogni pagina: apre la sessione e prepara il collegamento al database |
| `db/database.php` | l'**unico** file che parla col database (tutte le query stanno qui) |
| `db/creazione_db.sql` | il file da eseguire per **creare le tabelle** del database |
| `utils/functions.php` | piccole **funzioni di aiuto** (controllo se sei loggato, se sei admin, ecc.) |
| `template/base.php` | la **cornice** comune a tutte le pagine |
| `template/auth/` | le viste di **login** e **registrazione** |
| `css/` | i fogli di stile (l'aspetto grafico) |
| `js/` | il JavaScript (es. la conferma "Sei sicuro?", gli orari liberi) |
| `upload/` | dove finiscono le **immagini dei campi** |
| `api/disponibilita.php` | mostra gli **orari liberi** di un campo (l'unico pezzo con un po' di "magia" senza ricaricare) |

### I 4 file in root, spiegati uno a uno
Sono le "porte" comuni del sito, usate da tutti **prima** di entrare nelle aree.

**⚡ `bootstrap.php` — l'interruttore generale**
Si avvia all'inizio di **ogni** pagina (è sempre la prima riga: `require_once 'bootstrap.php'`).
Non è una pagina che visiti: **prepara il terreno**. Fa 4 cose:
1. apre la **sessione** (la memoria che ricorda chi sei mentre navighi);
2. definisce le **costanti** (`BASE_URL`, `UPLOAD_PATH`, `UPLOAD_URL`);
3. carica `utils/functions.php` e `db/database.php`;
4. crea **`$dbh`**, il collegamento al database (così ogni pagina può fare `$dbh->getCampi()`).

È come l'**accensione dell'auto**: prima di partire, accende tutto quello che serve.

**🚪 `index.php` — la porta d'ingresso (il LOGIN)**
La pagina che si apre **entrando nel sito**. Siccome il sito è "login-first", la porta è il login.
- se sei **già loggato** → ti manda alla tua area (admin o studente);
- se **invii il form** (email + password) → controlla con `$dbh->checkLogin()`: giuste → entri e vai
  nella tua area in base al **ruolo**; sbagliate → messaggio di errore;
- altrimenti → mostra il form (vista `template/auth/login-form.php`).

(Si chiama `index` perché è la pagina che si apre **in automatico** entrando in una cartella → la "home". Da noi la home è il login.)

**🚪 `logout.php` — l'uscita**
Il più semplice: è un file **d'azione** (come i `processa-*`). Cancella la sessione con `logout()` e
ti **rimanda al login**. Non mostra niente.

**📝 `registrazione.php` — crea un nuovo account**
Per un nuovo studente. Funziona come il login:
- se **invii il form** → controlla i dati (le password coincidono? email/username liberi?) e crea
  l'utente con `$dbh->registerUser()`; se va bene → ti logga e vai nell'area studente;
- altrimenti → mostra il form (vista `template/auth/registrazione-form.php`).

> **Nota importante:** per login e registrazione **NON** ci sono file `processa-*` separati.
> `index.php` e `registrazione.php` mostrano il form **e** lo elaborano nello **stesso file** (sono
> semplici, ed è così che faceva il prof nel laboratorio). Quindi non cercare `processa-login.php`: non esiste.

---

## 5. Le cartelle di supporto in dettaglio (`db`, `css`, `js`, `api`, `upload`, `utils`)

Queste cartelle non contengono pagine vere e proprie, ma il "dietro le quinte" del sito.
Prima un concetto che serve a capirle:

> **PHP gira sul SERVER, JavaScript gira nel BROWSER.**
> Il PHP lavora *prima* che la pagina arrivi a te (prepara tutto). Il JavaScript lavora *dopo*,
> nel tuo browser, mentre guardi la pagina (per le cose "dal vivo", senza ricaricare).

### 🗄️ `db/` — il database (la più importante)
Tutto ciò che riguarda l'archivio dei dati. Due file:
- **`creazione_db.sql`** → lo "stampo" del database. Lo esegui **una volta sola** in phpMyAdmin
  all'inizio: crea le **4 tabelle** (utente, sport, campo, prenotazione). Poi non lo tocchi più.
- **`database.php`** → contiene la classe **`DatabaseHelper`**, l'**unico** file che parla col database.

Come funziona: invece di scrivere comandi al database sparsi ovunque, li mettiamo **tutti qui**,
ognuno come una funzione (`getCampi`, `insertCampo`, `checkLogin`…). Il resto del sito non parla mai
direttamente col database: chiede a lui. Esempio:
```php
$campi = $dbh->getCampi();   // "dammi tutti i campi" → ci pensa DatabaseHelper
```
È come avere **un solo cameriere** che va in cucina (il database): tutti gli ordini passano da lui.
Vantaggi: ordine, sicurezza, e se cambi qualcosa lo correggi **in un posto solo**.

### 🎨 `css/` — l'aspetto grafico (come si vede)
Il CSS sono le regole che dicono **come appaiono le cose**: colori, dimensioni, spazi, posizione.
- **`style.css`** → il nostro foglio di stile.

Come funziona: usiamo **Bootstrap** (una libreria di stili già pronta, presa da internet) che fa il
**90% del lavoro** (layout, colori, versione mobile…). In `style.css` mettiamo **solo i ritocchi**
nostri. Il collegamento è in `template/base.php`, in alto (`<link rel="stylesheet" href=".../css/style.css">`).
È, in pratica, il **"vestito"** del sito.

### ⚙️ `js/` — il JavaScript (comportamento nel browser)
Il codice che gira **nel tuo browser**, per le cose che succedono **senza ricaricare** la pagina.
- **`prenotazione.js`** → serve soprattutto a questo: quando, nella pagina di prenotazione, **scegli
  una data**, lui va a chiedere quali **orari sono liberi** e te li mostra al volo.

Come funziona: il file viene caricato **in fondo** a `base.php` (`<script src=".../js/prenotazione.js">`).
Quando interagisci con la pagina, il JavaScript reagisce.

### 🔌 `api/` — gli "sportelli" per il JavaScript
Un file dentro `api/` è un file PHP che **non** restituisce una pagina intera, ma **solo dei dati**
(in un formato che il JavaScript capisce, chiamato **JSON**).
- **`disponibilita.php`** → uno "sportello": il JavaScript gli chiede *"quali orari sono liberi per il
  campo 5 il 12 luglio?"* e lui risponde con la lista degli orari.

Come lavorano insieme `js/` e `api/` (è l'unico pezzo di "magia senza ricaricare", l'effetto WOW):
```
Tu scegli una data in  campo.php
        │
        ▼
js/prenotazione.js   ──── "che orari sono liberi?" ────►  api/disponibilita.php
        ▲                                                        │ chiede al database
        │           ◄──── risponde con la lista (JSON) ──────────┘
        ▼
il JavaScript mostra gli orari liberi nella pagina, SENZA ricaricare
```
Differenza chiave: una pagina normale (es. `campi.php`) restituisce **tutta la schermata**; un file
`api/` restituisce **solo i dati**, perché a disegnarli ci pensa il JavaScript.

### 📁 `upload/` — le immagini dei campi
Non contiene codice, ma le **foto dei campi** caricate dall'admin (es. `upload/calcetto.jpg`).
Trucco importante: nel **database** salviamo solo il **nome** del file (es. `"calcetto.jpg"`), la foto
vera vive qui. Quando il sito mostra il campo, costruisce l'indirizzo `upload/calcetto.jpg` e la fa vedere.
```
DATABASE (tabella campo)            Cartella upload/
imgcampo: "calcetto.jpg"  ───────►  calcetto.jpg   ← la foto vera è qui
(salva solo il NOME)                basket.png
```
È anche **l'unica cartella che cresce** mentre usi il sito: ogni campo nuovo aggiunge una foto. Tutte
le altre cartelle (il codice) restano fisse.

### 🧰 `utils/` — la "cassetta degli attrezzi"
`utils` = *utilità*. Un solo file, **`functions.php`**, con tante **piccole funzioni di aiuto** usate in
tante pagine. Le scriviamo **una volta sola** qui e le usiamo ovunque (è caricato da `bootstrap.php`,
quindi è disponibile in tutte le pagine).

| Funzione | A cosa serve |
|---|---|
| `isUserLoggedIn()` | dice se sei **loggato** |
| `isStudente()` / `isAdmin()` | dice se sei **studente** o **admin** |
| `registerLoggedUser($user)` | al **login**, salva i tuoi dati nella sessione |
| `logout()` | cancella la sessione (**esci**) |
| `isActive($pagina)` | **evidenzia nel menu** la pagina in cui ti trovi |
| `getEmptyCampo()` | dà un "campo vuoto" per il **form di inserimento** |
| `getAction($n)` | traduce 1/2/3 in **"Inserisci / Modifica / Cancella"** |
| `uploadImage(...)` | **controlla e salva** un'immagine caricata |

Esempio (all'inizio di ogni pagina admin), che usa `isAdmin()`:
```php
if(!isAdmin()){ header("location: ".BASE_URL."index.php"); exit; }   // se non sei admin → al login
```

> **Legame tra le due cartelle:** la funzione **`uploadImage()`** (che sta in `utils/`) è proprio quella
> che **salva le foto dentro `upload/`** (controlla che sia un'immagine, non troppo grande, e la salva).

### In sintesi
| Cartella | A cosa serve | Dove "vive" |
|---|---|---|
| `db/`  | parlare col **database** (dati) | server (PHP) |
| `css/` | **come si vede** il sito | browser (aspetto) |
| `js/`  | **comportamento dal vivo** senza ricaricare | browser (JavaScript) |
| `api/` | **sportelli** che danno dati al JavaScript | server (PHP) → ponte verso `js/` |
| `upload/` | le **foto dei campi** caricate dall'admin | cartella di file |
| `utils/` | la **cassetta degli attrezzi** (funzioni di aiuto) | server (PHP) |

---

## 6. Come riconoscere a colpo d'occhio cosa fa un file

- `gestione-*` → ti **mostra una lista** (di campi, prenotazioni, utenti).
- `gestisci-*` → ti **mostra un form** da compilare.
- `processa-*` → **non mostra niente**: salva/elimina e ti **rimanda** indietro.
- plurale (`campi`) → **lista**; singolare (`campo`) → **una cosa sola** col dettaglio.
- nelle cartelle `template/...` ci sono le **viste** (il contenuto grafico), mai la logica.

---

## 7. Mini-glossario (parole che useremo)

- **Controllore (controller):** il file `.php` della pagina che prepara i dati e sceglie cosa mostrare.
- **Vista:** il pezzo di pagina con l'HTML che si vede (le card, le tabelle, i form). Sta in `template/`.
- **`$templateParams`:** la "scatola" dove il controllore mette le informazioni da passare alla vista.
- **Redirect:** quando il sito ti **sposta automaticamente** su un'altra pagina.
- **Sessione:** la "memoria" che tiene il fatto che tu sei loggato mentre navighi.
- **`action`:** un numero/parola che dice a un file cosa fare (es. 1 = aggiungi, 2 = modifica…).

---

### In una frase
**Clicchi → si apre una pagina nuova** (controllore → cornice `base.php` → vista). Quando **salvi
qualcosa**, il form va a un file **`processa-*`** che scrive nel database e ti **riporta** alla lista.
Semplice e sempre uguale, in tutte e due le aree.
