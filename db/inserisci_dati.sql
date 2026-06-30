-- =====================================================================
--  DATI DI ESEMPIO per il database "campisportivi"
--  Eseguire DOPO  db/creazione_db.sql  (lo schema).
--
--  Questo file si può RI-eseguire quante volte vuoi: all'inizio SVUOTA
--  le tabelle e poi le riempie di nuovo (così non dà errori di duplicati).
--
--  NB 1: le password NON sono in chiaro: sono salvate come HASH (bcrypt, password_hash).
--        Per accedere si usano comunque le password "vere" qui sotto:
--          - admin:    admin123
--          - studenti: password
--  NB 2: le foto dei campi sono già in upload/ (una per campo: beachvolley.jpg, padel-a.jpg, ...)
--  NB 3: le date delle prenotazioni usano CURDATE() (= oggi), così
--          "passate / oggi / future" sono sempre corrette al momento dell'import.
-- =====================================================================
USE `campisportivi`;

-- --------- Svuoto le tabelle (per poter ri-eseguire il file senza errori) ---------
-- Uso DELETE (non TRUNCATE) perché MariaDB non permette di troncare una tabella
-- a cui un'altra fa riferimento con una chiave esterna. L'ordine è "figli prima".
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM `prenotazione`;
DELETE FROM `campo`;
DELETE FROM `sport`;
DELETE FROM `utente`;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- UTENTI : 2 admin (email @unibo.it) + 10 studenti (email @studio.unibo.it)
-- =====================================================================
INSERT INTO `utente` (`idutente`,`nome`,`cognome`,`email`,`password`,`ruolo`) VALUES
(1,'Marco','Verdi','marco.verdi@unibo.it','$2y$10$rdjjIrQ6SaazexFx0xpHmOL66TxnMWPcZhQfDUar7a9g53WuVElBu','admin'),
(2,'Laura','Neri','laura.neri@unibo.it','$2y$10$rdjjIrQ6SaazexFx0xpHmOL66TxnMWPcZhQfDUar7a9g53WuVElBu','admin'),
(3,'Gino','Pino','gino.pino@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente'),
(4,'Maria','Rossi','maria.rossi@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente'),
(5,'Luca','Bianchi','luca.bianchi@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente'),
(6,'Sara','Russo','sara.russo@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente'),
(7,'Andrea','Ferrari','andrea.ferrari@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente'),
(8,'Giulia','Esposito','giulia.esposito@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente'),
(9,'Matteo','Romano','matteo.romano@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente'),
(10,'Chiara','Colombo','chiara.colombo@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente'),
(11,'Davide','Greco','davide.greco@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente'),
(12,'Francesca','Conti','francesca.conti@studio.unibo.it','$2y$10$VQN8RLhvkG7vdS9AcCfdOu/6SzEIO8cyRej8aQk9XndDh8WHU2x4q','studente');

-- =====================================================================
-- SPORT (i più praticati)
-- =====================================================================
INSERT INTO `sport` (`idsport`,`nomesport`) VALUES
(1,'Beach Volley'),
(2,'Padel'),
(3,'Calcetto a 5'),
(4,'Tennis'),
(5,'Basket'),
(6,'Pallavolo');

-- =====================================================================
-- CAMPI  (capienze adeguate allo sport; il "Campo Tennis 2" è chiuso = aperto 0)
-- =====================================================================
INSERT INTO `campo`
(`idcampo`,`nomecampo`,`descrizionecampo`,`luogocampo`,`tipocampo`,`capienzamax`,`orarioapertura`,`orariochiusura`,`aperto`,`imgcampo`,`sport`,`creatore`) VALUES
(1,'Campo Beach Volley 1','Campo da beach volley con sabbia, all''aperto.','Area Beach del Campus','outdoor',6,'09:00:00','21:00:00',1,'beachvolley.jpg',1,1),
(2,'Campo Padel A','Campo da padel coperto con pareti in vetro.','Centro Padel - Area 1','indoor',4,'08:00:00','23:00:00',1,'padel-a.jpg',2,1),
(3,'Campo Padel B','Campo da padel esterno panoramico.','Centro Padel - Area 2','outdoor',4,'09:00:00','21:00:00',1,'padel-b.jpg',2,2),
(4,'Campo Calcetto A','Campo da calcio a 5 in erba sintetica, con spogliatoi e illuminazione.','Polisportivo del Campus','outdoor',10,'08:00:00','22:00:00',1,'calcetto-a.png',3,1),
(5,'Campo Calcetto B','Campo da calcio a 5 indoor.','Palazzetto dello Sport','indoor',10,'08:00:00','23:00:00',1,'calcetto-b.webp',3,2),
(6,'Campo Tennis 1','Campo da tennis in terra battuta.','Area Tennis','outdoor',4,'08:00:00','20:00:00',1,'tennis-1.jpg',4,1),
(7,'Campo Tennis 2','Campo da tennis in cemento (in manutenzione).','Area Tennis','outdoor',4,'08:00:00','20:00:00',0,'tennis-2.jpg',4,2),
(8,'Palestra Basket','Campo da basket indoor con parquet regolamentare.','Palazzetto dello Sport','indoor',10,'08:00:00','22:00:00',1,'basket.webp',5,1),
(9,'Campo Pallavolo','Campo da pallavolo regolamentare al coperto.','Palazzetto dello Sport','indoor',12,'08:00:00','22:00:00',1,'pallavolo.jpg',6,2);

-- =====================================================================
-- PRENOTAZIONI : per OGNI campo un mix di passate / oggi / future
--   (idprenotazione e datacreazione si riempiono da soli)
--   date relative a oggi con CURDATE() ± INTERVAL ... DAY
-- =====================================================================
INSERT INTO `prenotazione`
(`utente`,`campo`,`dataprenotazione`,`orainizio`,`orafine`,`numpartecipanti`,`stato`) VALUES
-- Campo 1 - Beach Volley 1
(3, 1, CURDATE() - INTERVAL 14 DAY, '10:00:00','11:00:00', 4, 'confermata'),
(4, 1, CURDATE(),                   '18:00:00','19:00:00', 4, 'confermata'),
(5, 1, CURDATE() + INTERVAL 3 DAY,  '17:00:00','18:00:00', 6, 'confermata'),
(6, 1, CURDATE() + INTERVAL 9 DAY,  '19:00:00','20:00:00', 4, 'confermata'),

-- Campo 2 - Padel A
(7, 2, CURDATE() - INTERVAL 10 DAY, '09:00:00','10:00:00', 4, 'confermata'),
(8, 2, CURDATE(),                   '20:00:00','21:00:00', 2, 'confermata'),
(3, 2, CURDATE() + INTERVAL 5 DAY,  '18:00:00','19:00:00', 4, 'confermata'),

-- Campo 3 - Padel B
(9, 3, CURDATE() - INTERVAL 5 DAY,  '15:00:00','16:00:00', 4, 'confermata'),
(10,3, CURDATE() + INTERVAL 2 DAY,  '16:00:00','17:00:00', 2, 'confermata'),
(11,3, CURDATE() + INTERVAL 11 DAY, '11:00:00','12:00:00', 4, 'confermata'),

-- Campo 4 - Calcetto A
(12,4, CURDATE() - INTERVAL 7 DAY,  '18:00:00','19:00:00', 10,'confermata'),
(3, 4, CURDATE(),                   '19:00:00','20:00:00', 8, 'confermata'),
(4, 4, CURDATE() + INTERVAL 1 DAY,  '20:00:00','21:00:00', 10,'confermata'),
(5, 4, CURDATE() + INTERVAL 14 DAY, '17:00:00','18:00:00', 8, 'confermata'),

-- Campo 5 - Calcetto B
(6, 5, CURDATE() - INTERVAL 3 DAY,  '21:00:00','22:00:00', 10,'confermata'),
(7, 5, CURDATE(),                   '10:00:00','11:00:00', 8, 'confermata'),
(8, 5, CURDATE() + INTERVAL 6 DAY,  '18:00:00','19:00:00', 10,'confermata'),

-- Campo 6 - Tennis 1
(9, 6, CURDATE() - INTERVAL 12 DAY, '09:00:00','10:00:00', 2, 'confermata'),
(10,6, CURDATE() + INTERVAL 4 DAY,  '17:00:00','18:00:00', 4, 'confermata'),
(11,6, CURDATE() + INTERVAL 8 DAY,  '10:00:00','11:00:00', 2, 'confermata'),

-- Campo 7 - Tennis 2 (chiuso): solo una prenotazione passata, fatta prima della chiusura
(12,7, CURDATE() - INTERVAL 20 DAY, '15:00:00','16:00:00', 2, 'confermata'),

-- Campo 8 - Palestra Basket
(3, 8, CURDATE() - INTERVAL 6 DAY,  '20:00:00','21:00:00', 10,'confermata'),
(4, 8, CURDATE(),                   '17:00:00','18:00:00', 8, 'confermata'),
(5, 8, CURDATE() + INTERVAL 8 DAY,  '19:00:00','20:00:00', 10,'confermata'),
(6, 8, CURDATE() + INTERVAL 2 DAY,  '18:00:00','19:00:00', 8, 'cancellata'),

-- Campo 9 - Pallavolo
(7, 9, CURDATE() - INTERVAL 4 DAY,  '16:00:00','17:00:00', 12,'confermata'),
(8, 9, CURDATE(),                   '21:00:00','22:00:00', 10,'confermata'),
(9, 9, CURDATE() + INTERVAL 11 DAY, '18:00:00','19:00:00', 12,'confermata');
