-- =====================================================================
--  Database "campisportivi" — Prenotazione campi sportivi universitari
--  QUESTO FILE: SOLO LO SCHEMA (la struttura delle tabelle).
--  I DATI di esempio sono nel file separato:  db/inserisci_dati.sql
--  4 tabelle, solo tecniche viste in laboratorio.
-- =====================================================================

SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

CREATE SCHEMA IF NOT EXISTS `campisportivi` DEFAULT CHARACTER SET utf8mb4;
USE `campisportivi`;

-- ---------------------------------------------------------------------
-- utente: studenti e admin, distinti dal campo `ruolo`
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `utente` (
  `idutente` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `cognome` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `ruolo` VARCHAR(20) NOT NULL DEFAULT 'studente',   -- solo 'studente' oppure 'admin'
  `dataregistrazione` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idutente`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;

-- ---------------------------------------------------------------------
-- sport (Calcetto, Basket, Pallavolo, Tennis, Padel, ...)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sport` (
  `idsport` INT NOT NULL AUTO_INCREMENT,
  `nomesport` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idsport`))
ENGINE = InnoDB;

-- ---------------------------------------------------------------------
-- campo (l'entità principale)
--   sport    -> FK verso sport (a quale sport appartiene)
--   creatore -> FK verso utente (l'admin che lo ha aggiunto)
--   aperto   -> 1 = prenotabile, 0 = chiuso dall'admin
--   gli orari indicano in quali fasce si può prenotare
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `campo` (
  `idcampo` INT NOT NULL AUTO_INCREMENT,
  `nomecampo` VARCHAR(100) NOT NULL,
  `descrizionecampo` TEXT NULL,
  `luogocampo` VARCHAR(100) NOT NULL,
  `tipocampo` VARCHAR(20) NOT NULL,              -- 'indoor' oppure 'outdoor'
  `capienzamax` INT NOT NULL,
  `orarioapertura` TIME NOT NULL,
  `orariochiusura` TIME NOT NULL,
  `aperto` TINYINT NOT NULL DEFAULT 1,
  `imgcampo` VARCHAR(100) NOT NULL,
  `sport` INT NOT NULL,
  `creatore` INT NOT NULL,
  PRIMARY KEY (`idcampo`),
  INDEX `fk_campo_sport_idx` (`sport` ASC),
  INDEX `fk_campo_utente_idx` (`creatore` ASC),
  CONSTRAINT `fk_campo_sport`  FOREIGN KEY (`sport`)    REFERENCES `sport`  (`idsport`),
  CONSTRAINT `fk_campo_utente` FOREIGN KEY (`creatore`) REFERENCES `utente` (`idutente`))
ENGINE = InnoDB;

-- ---------------------------------------------------------------------
-- prenotazione (collega utente e campo con data e fascia oraria)
--   È il cuore del servizio (relazione N:N "arricchita").
--   stato: 'confermata' oppure 'cancellata' (annullare ≠ eliminare la riga)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `prenotazione` (
  `idprenotazione` INT NOT NULL AUTO_INCREMENT,
  `utente` INT NOT NULL,
  `campo` INT NOT NULL,
  `dataprenotazione` DATE NOT NULL,
  `orainizio` TIME NOT NULL,
  `orafine` TIME NOT NULL,
  `numpartecipanti` INT NOT NULL DEFAULT 1,
  `stato` VARCHAR(20) NOT NULL DEFAULT 'confermata',  -- 'confermata' oppure 'cancellata'
  `datacreazione` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idprenotazione`),
  INDEX `fk_pren_utente_idx` (`utente` ASC),
  INDEX `fk_pren_campo_idx` (`campo` ASC),
  CONSTRAINT `fk_pren_utente` FOREIGN KEY (`utente`) REFERENCES `utente` (`idutente`),
  CONSTRAINT `fk_pren_campo`  FOREIGN KEY (`campo`)  REFERENCES `campo`  (`idcampo`))
ENGINE = InnoDB;

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
