# Il motore del sito: Controller, Template e Database

Questa guida spiega in modo semplice **come funziona "dietro le quinte"** ogni pagina del sito:
chi prende i dati, chi li mostra e come si parla con il database. Capito questo, hai capito **tutto**,
perché **ogni** pagina funziona allo stesso identico modo.

---

## Le 3 figure in gioco

Per fare una pagina servono sempre **tre attori**, ognuno con un compito preciso:

1. **Il CONTROLLER** → il *coordinatore* della pagina (il file `.php` che apri).
2. **Il DATABASE** (raggiunto tramite il **DatabaseHelper**) → l'*archivio dei dati*.
3. **Il TEMPLATE** → la *grafica* (la cornice `base.php` + la vista col contenuto).

> Regola fondamentale: **ognuno fa solo il suo mestiere.** Il controller non disegna, la grafica non
> fa conti, e solo il DatabaseHelper parla col database. Questa divisione è tutto il segreto.


## 1. Il CONTROLLER — il coordinatore

È il file `.php` della pagina (es. `studente/campi.php`). **Non** contiene grafica e **non** scrive
comandi al database. Fa solo da regista, in **3 mosse**:

1. **Chiede i dati** al DatabaseHelper (es. "dammi tutti i campi").
2. **Mette i dati in una scatola** chiamata `$templateParams` (insieme al titolo e al nome della vista).
3. **Richiama il template** per far costruire e mostrare la pagina.

---

## 2. Il DATABASE e il DatabaseHelper — i dati

- Il **database** è l'archivio: le nostre 4 tabelle (utente, sport, campo, prenotazione).
- Il **DatabaseHelper** (`db/database.php`) è l'**unico** che ci parla. Ogni richiesta è una funzione:
  `getCampi()`, `insertCampo()`, `checkLogin()`…

Il controller **non tocca mai** il database direttamente: chiede sempre al DatabaseHelper. Così tutti i
comandi al database stanno in **un posto solo** (ordine + sicurezza).

---

## 3. Il TEMPLATE — la grafica

Il template è fatto di due parti:

- **`base.php`** = la **cornice** uguale per tutte le pagine (intestazione, menu, piè di pagina).
  Si scrive **una volta sola**.
- **La vista** (es. `template/studente/lista-campi.php`) = il **contenuto** che cambia da pagina a
  pagina (la lista, il form…).

La vista prende i dati dalla scatola `$templateParams` e li **stampa e basta**. Nessun comando al
database, nessun conto: solo "mostra".

---

## La scatola `$templateParams`

È il "vassoio" su cui il controller appoggia tutto ciò che serve alla grafica. Di solito contiene:

| Chiave | Cosa contiene |
|---|---|
| `titolo` | il titolo della pagina (quello in alto nella scheda del browser) |
| `nome` | **quale vista** mostrare (es. `"studente/lista-campi.php"`) |
| *(i dati)* | l'elenco dei campi, una prenotazione, un utente… |

Il controller **riempie** la scatola, la grafica la **legge**. È così che si passano le informazioni.

---

### Come appare nel codice (esempio di come verrà)

**Il controller — `studente/campi.php`:**
```php
<?php
require_once __DIR__ . '/../bootstrap.php';   // avvia tutto e crea $dbh

// 1) chiedo i dati al DatabaseHelper
$templateParams["campi"] = $dbh->getCampi();

// 2) dico il titolo e quale vista usare
$templateParams["titolo"] = "Prenota un campo";
$templateParams["nome"]   = "studente/lista-campi.php";

// 3) mostro la pagina (cornice + vista)
require __DIR__ . '/../template/base.php';
?>
```

**Il DatabaseHelper — il metodo dentro `db/database.php`:**
```php
public function getCampi(){
    $stmt = $this->db->prepare("SELECT * FROM campo");      // la domanda al database
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);    // la risposta: l'elenco dei campi
}
```

**La cornice — il centro di `template/base.php`:**
```php
<main>
    <?php require __DIR__ . "/" . $templateParams["nome"]; ?>   <!-- inserisce la vista scelta -->
</main>
```

**La vista — `template/studente/lista-campi.php`:**
```php
<?php foreach($templateParams["campi"] as $campo): ?>
    <div class="card">
        <h3><?php echo $campo["nomecampo"]; ?></h3>
        <a href="campo.php?id=<?php echo $campo["idcampo"]; ?>">Prenota</a>
    </div>
<?php endforeach; ?>
```

Vedi come ognuno fa **solo** il suo pezzo? Il controller coordina, il DatabaseHelper porta i dati, la
vista disegna. Nessuno fa il lavoro dell'altro.

---

## E quando SALVI qualcosa? (il giro inverso)

Finora abbiamo visto il giro per **mostrare** dati (leggere). Quando invece **scrivi** (prenoti, crei
un campo…), il giro è simile ma al contrario e **non** c'è una grafica da mostrare:

```
FORM (es. prenota)
   │ invii i dati
   ▼
processa-*.php (controller d'azione)
   │ chiede al DatabaseHelper di SCRIVERE: $dbh->insertPrenotazione(...)
   ▼
DatabaseHelper → scrive nel DATABASE
   │
   ▼
redirect: ti rimanda a una pagina normale (es. "le mie prenotazioni")
```

Qui il "template" non serve: il file `processa-*` fa l'azione e ti **rimanda** a un'altra pagina (che a
quel punto rifarà il giro normale: controller → DatabaseHelper → template).

---

## Perché dividere il lavoro così? (i vantaggi)

- **Ordine:** sai sempre dove guardare → logica nel controller, grafica nelle viste, query nel DatabaseHelper.
- **Riuso:** la cornice (`base.php`) è scritta **una volta** e vale per tutte le pagine.
- **Sicurezza:** tutti i comandi al database stanno in **un posto solo**, fatti in modo sicuro (prepared statement).
- **Facile da modificare:** cambi la grafica senza toccare la logica, e viceversa.
- **Facile da spiegare** (anche all'esame!): ogni pagina segue **sempre lo stesso schema**.

---

## In una frase

> Il **controller** fa da regista: **chiede i dati** al **DatabaseHelper**, li mette nella **scatola
> `$templateParams`**, e poi il **template** (cornice `base.php` + vista) **li mostra**. Quando devi
> **salvare**, un file **`processa-*`** chiede al DatabaseHelper di scrivere e poi ti **rimanda** indietro.

Ogni singola pagina del sito è solo una variazione di questo schema.
