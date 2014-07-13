
-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `iduser` INT(15) NOT NULL AUTO_INCREMENT,
  `mail` VARCHAR(255) NOT NULL,
  `firstname` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `pass` VARCHAR(255) NOT NULL,
  `token` VARCHAR(64) NULL,
  `lastlogin` INT(15) NULL,
  `created` INT(15) NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE INDEX `mail_UNIQUE` (`mail` ASC),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC))
ENGINE = InnoDB;
