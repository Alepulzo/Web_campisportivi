<?php
/* DatabaseHelper — unico file che parla col database. */
class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        // 1) creo la connessione al database con mysqli
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        // 2) se la connessione fallisce, fermo tutto con un messaggio chiaro
        if($this->db->connect_error){
            die("Connessione al database fallita: " . $this->db->connect_error);
        }
        // 3) imposto la codifica giusta per gli accenti
        $this->db->set_charset("utf8mb4");
    }

    // UTENTI (login / registrazione)

    // Verifica le credenziali: cerca l'utente per email, poi controlla la password con password_verify() contro l'hash salvato.
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

    // Dati di un utente dal suo id (per la pagina profilo).
    public function getUserById($idutente){
        $stmt = $this->db->prepare("SELECT idutente, nome, cognome, email, ruolo, dataregistrazione FROM utente WHERE idutente = ?");
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return count($result) === 1 ? $result[0] : null;
    }

    // Verifica la password attuale di un utente dato il suo id (serve per il cambio password dal profilo).
    public function checkPasswordById($idutente, $password){
        $stmt = $this->db->prepare("SELECT password FROM utente WHERE idutente = ?");
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return count($result) === 1 && password_verify($password, $result[0]["password"]);
    }

    // Cambia la password di un utente. Viene salvata come hash sicuro, mai in chiaro.
    public function updatePassword($idutente, $nuovaPassword){
        $hash = password_hash($nuovaPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE utente SET password = ? WHERE idutente = ?");
        $stmt->bind_param('si', $hash, $idutente);
        return $stmt->execute();
    }

    // CAMPI (lettura)

    // Ritorna TUTTI i campi con il nome dello sport ordinati per nome.
    public function getCampi(){
        $stmt = $this->db->prepare(
            "SELECT c.idcampo, c.nomecampo, c.luogocampo, c.tipocampo, c.aperto, c.imgcampo,
                    c.orarioapertura, c.orariochiusura, s.nomesport
             FROM campo c
             INNER JOIN sport s ON c.sport = s.idsport
             ORDER BY c.nomecampo"
        );
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Solo i campi aperti (prenotabili), per il menù del form prenotazione.
    public function getCampiAperti(){
        $stmt = $this->db->prepare("SELECT idcampo, nomecampo, capienzamax FROM campo WHERE aperto = 1 ORDER BY nomecampo");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Ritorna UN campo dal suo id (con anche il nome dello sport, per il dettaglio).
    public function getCampoById($idcampo){
        $stmt = $this->db->prepare(
            "SELECT c.idcampo, c.nomecampo, c.descrizionecampo, c.luogocampo, c.tipocampo,
                    c.capienzamax, c.orarioapertura, c.orariochiusura, c.aperto, c.imgcampo,
                    c.sport, c.creatore, s.nomesport
             FROM campo c INNER JOIN sport s ON c.sport = s.idsport
             WHERE c.idcampo = ?"
        );
        $stmt->bind_param('i', $idcampo);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return count($result) === 1 ? $result[0] : null;
    }

    // CAMPI (scrittura)

    // Cambia lo stato di un campo: 1 = aperto, 0 = chiuso. Ritorna true se riesce.
    // Chiudendo (0), annulla anche le prenotazioni future di quel campo.
    public function setStatoCampo($idcampo, $aperto){
        $stmt = $this->db->prepare("UPDATE campo SET aperto = ? WHERE idcampo = ?");
        $stmt->bind_param('ii', $aperto, $idcampo);
        $ok = $stmt->execute();

        // se sto chiudendo il campo, annullo le sue prenotazioni future (da oggi in poi)
        if($aperto == 0){
            $stmt = $this->db->prepare(
                "UPDATE prenotazione SET stato = 'cancellata'
                 WHERE campo = ? AND stato = 'confermata' AND dataprenotazione >= CURDATE()"
            );
            $stmt->bind_param('i', $idcampo);
            $stmt->execute();
        }
        return $ok;
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

    // SPORT 

    // Ritorna tutti gli sport.
    public function getSport(){
        $stmt = $this->db->prepare("SELECT idsport, nomesport FROM sport ORDER BY nomesport");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // DASHBOARD

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

    // Numero di campi aperti (prenotabili).
    public function countCampiAperti(){
        $stmt = $this->db->prepare("SELECT COUNT(*) AS n FROM campo WHERE aperto = 1");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["n"];
    }

    // PRENOTAZIONI

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

    // Prenotazioni confermate di un campo in una data (per calcolare gli slot liberi).
    // $escludi = id di una prenotazione da ignorare (serve in modifica, per non contarsi da sola).
    public function getPrenotazioniByCampoEData($idcampo, $data, $escludi = 0){
        $stmt = $this->db->prepare(
            "SELECT orainizio, orafine FROM prenotazione
             WHERE campo = ? AND dataprenotazione = ? AND stato = 'confermata' AND idprenotazione != ?"
        );
        $stmt->bind_param('isi', $idcampo, $data, $escludi);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // true se la fascia (orainizio–orafine) è libera su quel campo/giorno (nessuna sovrapposizione).
    public function isSlotLibero($idcampo, $data, $orainizio, $orafine, $escludi = 0){
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS n FROM prenotazione
             WHERE campo = ? AND dataprenotazione = ? AND stato = 'confermata'
               AND idprenotazione != ? AND orainizio < ? AND orafine > ?"
        );
        $stmt->bind_param('isiss', $idcampo, $data, $escludi, $orafine, $orainizio);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $r[0]["n"] == 0;
    }

    // true se lo studente NON ha già una prenotazione in quella fascia (su qualsiasi campo).
    public function isStudenteLibero($idutente, $data, $orainizio, $orafine, $escludi = 0){
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS n FROM prenotazione
             WHERE utente = ? AND dataprenotazione = ? AND stato = 'confermata'
               AND idprenotazione != ? AND orainizio < ? AND orafine > ?"
        );
        $stmt->bind_param('isiss', $idutente, $data, $escludi, $orafine, $orainizio);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $r[0]["n"] == 0;
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

    // Tutte le prenotazioni di uno studente (con nome campo), per "le mie prenotazioni".
    public function getPrenotazioniByUser($idutente){
        $stmt = $this->db->prepare(
            "SELECT p.idprenotazione, p.dataprenotazione, p.orainizio, p.orafine,
                    p.numpartecipanti, p.stato, p.campo, c.nomecampo
             FROM prenotazione p
             INNER JOIN campo c ON p.campo = c.idcampo
             WHERE p.utente = ?
             ORDER BY p.dataprenotazione, p.orainizio"
        );
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Le prossime $n prenotazioni confermate di uno studente (da oggi in poi), per la dashboard.
    public function getProssimePrenotazioni($idutente, $n){
        $stmt = $this->db->prepare(
            "SELECT p.idprenotazione, p.dataprenotazione, p.orainizio, p.orafine, p.numpartecipanti, c.nomecampo
             FROM prenotazione p
             INNER JOIN campo c ON p.campo = c.idcampo
             WHERE p.utente = ? AND p.stato = 'confermata' AND p.dataprenotazione >= CURDATE()
             ORDER BY p.dataprenotazione, p.orainizio
             LIMIT ?"
        );
        $stmt->bind_param('ii', $idutente, $n);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Numero di prenotazioni confermate fatte da uno studente (dashboard/profilo).
    public function countPrenotazioniByUser($idutente){
        $stmt = $this->db->prepare("SELECT COUNT(*) AS n FROM prenotazione WHERE utente = ? AND stato = 'confermata'");
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["n"];
    }

    // Numero di prenotazioni future confermate di uno studente (per la card della dashboard).
    public function countProssimePrenotazioni($idutente){
        $stmt = $this->db->prepare("SELECT COUNT(*) AS n FROM prenotazione WHERE utente = ? AND stato = 'confermata' AND dataprenotazione >= CURDATE()");
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["n"];
    }

    // Lo studente annulla una SUA prenotazione: mette stato = 'cancellata' solo se è sua.
    public function annullaPrenotazione($idprenotazione, $idutente){
        $stmt = $this->db->prepare("UPDATE prenotazione SET stato = 'cancellata' WHERE idprenotazione = ? AND utente = ?");
        $stmt->bind_param('ii', $idprenotazione, $idutente);
        return $stmt->execute();
    }

    // Regole di una prenotazione: ritorna il messaggio d'errore, o "" se è tutto ok.
    // Condivisa tra admin e studente. ($escludi: prenotazione da ignorare, in modifica)
    public function erroriPrenotazione($idutente, $idcampo, $data, $orainizio, $orafine, $numpartecipanti, $escludi = 0){
        $oggi = date("Y-m-d");

        // niente giorni passati
        if($data < $oggi){
            return "Non puoi prenotare un giorno già passato.";
        }
        // non troppo in anticipo: al massimo GIORNI_ANTICIPO giorni avanti
        $limite = date("Y-m-d", strtotime("+" . GIORNI_ANTICIPO . " days"));
        if($data > $limite){
            return "Puoi prenotare al massimo " . GIORNI_ANTICIPO . " giorni in anticipo (fino al " . date("d/m/Y", strtotime($limite)) . ").";
        }
        // se è oggi, la fascia non deve essere già iniziata
        if($data === $oggi && substr($orainizio, 0, 5) <= date("H:i")){
            return "Questa fascia oraria è già passata.";
        }

        // il campo deve esistere ed essere aperto
        $campo = $this->getCampoById($idcampo);
        if($campo === null){
            return "Campo non valido.";
        }
        if($campo["aperto"] == 0){
            return "Questo campo è chiuso: non è prenotabile.";
        }

        // la fascia deve stare dentro l'orario di apertura del campo
        $apertura = substr($campo["orarioapertura"], 0, 5);
        $chiusura = substr($campo["orariochiusura"], 0, 5);
        if(substr($orainizio, 0, 5) < $apertura || substr($orafine, 0, 5) > $chiusura){
            return "L'orario scelto è fuori dall'apertura del campo (" . $apertura . "–" . $chiusura . ").";
        }

        // partecipanti entro la capienza del campo
        if($numpartecipanti > $campo["capienzamax"]){
            return "Troppi partecipanti: la capienza massima di questo campo è " . $campo["capienzamax"] . ".";
        }

        // la fascia non deve essere già occupata (ricontrollo: due invii nello stesso momento)
        if(!$this->isSlotLibero($idcampo, $data, $orainizio, $orafine, $escludi)){
            return "Questa fascia è appena stata prenotata: scegline un'altra.";
        }

        // lo studente non può avere un'altra prenotazione nella stessa fascia (anche su un altro campo)
        if(!$this->isStudenteLibero($idutente, $data, $orainizio, $orafine, $escludi)){
            return "Lo studente ha già una prenotazione in questa fascia oraria.";
        }

        return "";
    }

    // UTENTI (gestione admin)

    // Tutti gli utenti.
    public function getAllUtenti(){
        $stmt = $this->db->prepare("SELECT idutente, nome, cognome, email, ruolo, dataregistrazione FROM utente ORDER BY cognome, nome");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Elimina un utente, ma solo se è uno studente (mai un admin).
    public function deleteUtente($idutente){
        // controllo il ruolo: gli admin non si eliminano
        $stmt = $this->db->prepare("SELECT ruolo FROM utente WHERE idutente = ?");
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if(count($r) === 0 || $r[0]["ruolo"] !== 'studente'){
            return false;
        }
        // prima le sue prenotazioni (vincolo di chiave esterna), poi l'utente
        $stmt = $this->db->prepare("DELETE FROM prenotazione WHERE utente = ?");
        $stmt->bind_param('i', $idutente);
        $stmt->execute();

        $stmt = $this->db->prepare("DELETE FROM utente WHERE idutente = ?");
        $stmt->bind_param('i', $idutente);
        return $stmt->execute();
    }
}
?>
