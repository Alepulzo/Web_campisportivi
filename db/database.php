<?php
/* ============================================================
 * DatabaseHelper  —  STRATO DI ACCESSO AI DATI
 * ------------------------------------------------------------
 * UNICO file che parla col database. Ogni query è un metodo e usa
 * SEMPRE i prepared statement (prepare + bind_param).
 *
 * Sotto: ELENCO dei metodi da implementare, raggruppati per funzione
 * del sito.
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
    // con password_verify() contro l'hash salvato.
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

    // Registra un nuovo studente. La password viene salvata come HASH sicuro, mai in chiaro.
    public function registerUser($nome, $cognome, $email, $password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO utente (nome, cognome, email, password, ruolo) VALUES (?, ?, ?, ?, 'studente')");
        $stmt->bind_param('ssss', $nome, $cognome, $email, $hash);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // CAMPI (lettura)

    // Ritorna TUTTI i campi con il nome dello sport ordinati per nome.
    public function getCampi(){
        $stmt = $this->db->prepare(
            "SELECT c.idcampo, c.nomecampo, c.luogocampo, c.tipocampo, c.aperto, c.imgcampo, s.nomesport
             FROM campo c
             INNER JOIN sport s ON c.sport = s.idsport
             ORDER BY c.nomecampo"
        );
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Ritorna UN campo dal suo id
    public function getCampoById($idcampo){
        $stmt = $this->db->prepare(
            "SELECT idcampo, nomecampo, descrizionecampo, luogocampo, tipocampo,
                    capienzamax, orarioapertura, orariochiusura, aperto, imgcampo, sport, creatore
             FROM campo WHERE idcampo = ?"
        );
        $stmt->bind_param('i', $idcampo);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return count($result) === 1 ? $result[0] : null;
    }

    // CAMPI (scrittura)

    // Cambia lo stato di un campo: 1 = aperto, 0 = chiuso. Ritorna true se riesce.
    public function setStatoCampo($idcampo, $aperto){
        $stmt = $this->db->prepare("UPDATE campo SET aperto = ? WHERE idcampo = ?");
        $stmt->bind_param('ii', $aperto, $idcampo);
        return $stmt->execute();
    }

    // Aggiunge un nuovo campo.
    public function insertCampo($nomecampo, $descrizione, $luogocampo, $tipocampo,
                                $capienzamax, $orarioapertura, $orariochiusura,
                                $aperto, $imgcampo, $sport, $creatore){
        $stmt = $this->db->prepare(
            "INSERT INTO campo
               (nomecampo, descrizionecampo, luogocampo, tipocampo, capienzamax,
                orarioapertura, orariochiusura, aperto, imgcampo, sport, creatore)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssssissisii',
            $nomecampo, $descrizione, $luogocampo, $tipocampo, $capienzamax,
            $orarioapertura, $orariochiusura, $aperto, $imgcampo, $sport, $creatore);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Modifica un campo esistente.
    public function updateCampo($idcampo, $nomecampo, $descrizione, $luogocampo, $tipocampo,
                                $capienzamax, $orarioapertura, $orariochiusura,
                                $aperto, $imgcampo, $sport){
        $stmt = $this->db->prepare(
            "UPDATE campo SET
                nomecampo = ?, descrizionecampo = ?, luogocampo = ?, tipocampo = ?,
                capienzamax = ?, orarioapertura = ?, orariochiusura = ?, aperto = ?,
                imgcampo = ?, sport = ?
             WHERE idcampo = ?"
        );
        // tipi: s s s s i s s i s i i
        $stmt->bind_param('ssssissisii',
            $nomecampo, $descrizione, $luogocampo, $tipocampo, $capienzamax,
            $orarioapertura, $orariochiusura, $aperto, $imgcampo, $sport, $idcampo);
        return $stmt->execute();
    }

    // Elimina un campo e tutte le sue prenotazioni.
    public function deleteCampo($idcampo){
        $stmt = $this->db->prepare("DELETE FROM prenotazione WHERE campo = ?");
        $stmt->bind_param('i', $idcampo);
        $stmt->execute();

        $stmt = $this->db->prepare("DELETE FROM campo WHERE idcampo = ?");
        $stmt->bind_param('i', $idcampo);
        return $stmt->execute();
    }

    /* ===================== SPORT ===================== */

    // Ritorna tutti gli sport.
    public function getSport(){
        $stmt = $this->db->prepare("SELECT idsport, nomesport FROM sport ORDER BY nomesport");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* ===================== DASHBOARD ===================== */

    // Prenotazioni di oggi (con studente e campo), solo confermate.
    public function getPrenotazioniOggi(){
        $stmt = $this->db->prepare(
            "SELECT p.idprenotazione, p.orainizio, p.orafine, p.numpartecipanti,
                    u.nome, u.cognome, c.nomecampo
             FROM prenotazione p
             INNER JOIN utente u ON p.utente = u.idutente
             INNER JOIN campo  c ON p.campo  = c.idcampo
             WHERE p.dataprenotazione = CURDATE() AND p.stato = 'confermata'
             ORDER BY p.orainizio"
        );
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Numero di campi.
    public function countCampi(){
        $stmt = $this->db->prepare("SELECT COUNT(*) AS n FROM campo");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["n"];
    }

    // Numero di campi chiusi.
    public function countCampiChiusi(){
        $stmt = $this->db->prepare("SELECT COUNT(*) AS n FROM campo WHERE aperto = 0");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["n"];
    }

    /* ===================== PRENOTAZIONI ===================== */

    // Tutte le prenotazioni con studente e campo, ordinate per data.
    public function getAllPrenotazioni(){
        $stmt = $this->db->prepare(
            "SELECT p.idprenotazione, p.dataprenotazione, p.orainizio, p.orafine,
                    p.numpartecipanti, p.stato, u.nome, u.cognome, c.nomecampo
             FROM prenotazione p
             INNER JOIN utente u ON p.utente = u.idutente
             INNER JOIN campo  c ON p.campo  = c.idcampo
             ORDER BY p.dataprenotazione, p.orainizio"
        );
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Una prenotazione dal suo id (per pre-compilare il form di modifica), o null.
    public function getPrenotazioneById($idprenotazione){
        $stmt = $this->db->prepare(
            "SELECT idprenotazione, utente, campo, dataprenotazione, orainizio, orafine, numpartecipanti, stato
             FROM prenotazione WHERE idprenotazione = ?"
        );
        $stmt->bind_param('i', $idprenotazione);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return count($result) === 1 ? $result[0] : null;
    }

    // Elenco degli studenti (per il menu a tendina del form prenotazione).
    public function getStudenti(){
        $stmt = $this->db->prepare("SELECT idutente, nome, cognome FROM utente WHERE ruolo = 'studente' ORDER BY cognome, nome");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Aggiunge una prenotazione (stato 'confermata' di default).
    public function insertPrenotazione($utente, $campo, $data, $orainizio, $orafine, $numpartecipanti){
        $stmt = $this->db->prepare(
            "INSERT INTO prenotazione (utente, campo, dataprenotazione, orainizio, orafine, numpartecipanti)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('iisssi', $utente, $campo, $data, $orainizio, $orafine, $numpartecipanti);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Modifica una prenotazione esistente.
    public function updatePrenotazione($idprenotazione, $utente, $campo, $data, $orainizio, $orafine, $numpartecipanti){
        $stmt = $this->db->prepare(
            "UPDATE prenotazione SET utente = ?, campo = ?, dataprenotazione = ?, orainizio = ?, orafine = ?, numpartecipanti = ?
             WHERE idprenotazione = ?"
        );
        $stmt->bind_param('iisssii', $utente, $campo, $data, $orainizio, $orafine, $numpartecipanti, $idprenotazione);
        return $stmt->execute();
    }

    // Annulla una prenotazione: mette stato = 'cancellata' (non elimina la riga).
    public function annullaPrenotazioneAdmin($idprenotazione){
        $stmt = $this->db->prepare("UPDATE prenotazione SET stato = 'cancellata' WHERE idprenotazione = ?");
        $stmt->bind_param('i', $idprenotazione);
        return $stmt->execute();
    }

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
