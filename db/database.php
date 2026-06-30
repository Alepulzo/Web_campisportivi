<?php
/* ============================================================
 * DatabaseHelper  —  STRATO DI ACCESSO AI DATI
 * ------------------------------------------------------------
 * UNICO file che parla col database. Ogni query è un metodo e usa
 * SEMPRE i prepared statement (prepare + bind_param), come nei lab.
 *
 * Sotto: ELENCO dei metodi da implementare, raggruppati per funzione
 * del sito. Per ora sono segnaposto commentati: li riempiremo a mano.
 * ============================================================ */
class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        // 1) creo la connessione al database con mysqli
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        // 2) se la connessione fallisce, fermo tutto con un messaggio chiaro
        if($this->db->connect_error){
            die("Connessione al database fallita: " . $this->db->connect_error);
        }
        // 3) imposto la codifica giusta per gli accenti (à, è, ...)
        $this->db->set_charset("utf8mb4");
    }

    // UTENTI (login / registrazione)

    // Verifica le credenziali: cerca l'utente per email, poi controlla la password
    // con password_verify() contro l'hash salvato. Ritorna l'utente (0 righe = login fallito).
    public function checkLogin($email, $password){
        $stmt = $this->db->prepare("SELECT idutente, nome, cognome, email, ruolo, password FROM utente WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if(count($result) == 1 && password_verify($password, $result[0]["password"])){
            unset($result[0]["password"]);   // non faccio uscire l'hash dal DatabaseHelper
            return $result;
        }
        return array();   // login fallito
    }

    // Ritorna le righe con quell'email (serve per controllare se è già registrata).
    public function getUserByEmail($email){
        $stmt = $this->db->prepare("SELECT idutente FROM utente WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Registra un nuovo studente. La password viene salvata come HASH sicuro (bcrypt),
    // mai in chiaro. Ritorna l'id appena creato.
    public function registerUser($nome, $cognome, $email, $password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO utente (nome, cognome, email, password, ruolo) VALUES (?, ?, ?, ?, 'studente')");
        $stmt->bind_param('ssss', $nome, $cognome, $email, $hash);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /* ===================== SPORT ===================== */
    // getSport()                          -> tutti gli sport (per filtri/menu)

    /* ===================== CAMPI (lettura) ===================== */
    // getCampi($n = -1)                   -> elenco campi (join con sport); filtrabile
    // getCampiBySport($idsport)           -> campi di uno sport
    // getCampoById($idcampo)              -> dettaglio di un campo
    // getCampiInEvidenza($n)              -> n campi per la dashboard studente

    /* ===================== CAMPI (scrittura - CRUD admin) ===================== */
    // insertCampo(...)                    -> aggiunge un campo
    // updateCampo(...)                    -> modifica un campo
    // deleteCampo($idcampo)               -> elimina un campo
    // setStatoCampo($idcampo, $aperto)    -> chiude (0) / riapre (1) un campo

    /* ===================== UTENTI (login / registrazione / profilo) ===================== */
    // checkLogin($email, $password)       -> verifica credenziali, ritorna l'utente
    // registerUser(...)                   -> registra un nuovo studente
    // getUserByEmail($email)              -> per controllare email già usata
    // getUserById($idutente)              -> dati del profilo
    // updateProfilo($idutente, ...)       -> aggiorna i dati del profilo

    /* ===================== UTENTI (gestione admin) ===================== */
    // getAllUtenti()                      -> elenco di tutti gli utenti
    // deleteUtente($idutente)             -> elimina un utente

    /* ===================== PRENOTAZIONI ===================== */
    // insertPrenotazione($utente,$campo,$data,$orainizio,$orafine,$partecipanti)
    // getPrenotazioniByUser($idutente)            -> "le mie prenotazioni"
    // getProssimePrenotazioni($idutente, $n)      -> per la dashboard studente
    // annullaPrenotazione($idprenotazione,$idutente) -> lo studente annulla la SUA
    // getAllPrenotazioni()                        -> tutte (gestione admin, join utente+campo)
    // getPrenotazioniByCampo($idcampo)            -> prenotazioni di un campo
    // annullaPrenotazioneAdmin($idprenotazione)   -> l'admin annulla una prenotazione
    // getPrenotazioniByCampoEData($idcampo,$data) -> per calcolare gli orari liberi
    // isSlotLibero($idcampo,$data,$orainizio,$orafine) -> evita doppie prenotazioni

    /* ===================== STATISTICHE (dashboard) ===================== */
    // countCampi()            -> n. campi (dashboard admin)
    // countUtenti()           -> n. utenti (dashboard admin)
    // countPrenotazioni()     -> n. prenotazioni totali (dashboard admin)
    // countPrenotazioniByUser($idutente) -> n. prenotazioni dello studente
}
?>
