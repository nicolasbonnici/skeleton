
-- -----------------------------------------------------
-- Table `todo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `todo` ;

CREATE TABLE IF NOT EXISTS `todo` (
  `idtodo` INT(15) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(45) NOT NULL,
  `content` TEXT NOT NULL,
  `deadline` INT(15) NULL,
  `lastupdate` INT(15) NOT NULL,
  `created` INT(15) NOT NULL,
  `user_iduser` INT(15) NOT NULL,
  PRIMARY KEY (`idtodo`, `user_iduser`),
  INDEX `fk_Todo_users1_idx` (`user_iduser` ASC),
  CONSTRAINT `fk_Todo_users1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;  
