-- -----------------------------------------------------
-- Table `permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `permission` ;

CREATE TABLE IF NOT EXISTS `permission` (
  `idpermission` INT(15) NOT NULL AUTO_INCREMENT,
  `group_idgroup` INT(15) NOT NULL,
  `ressource_idressource` INT(15) NOT NULL,
  `permission` TEXT NULL,
  PRIMARY KEY (`idpermission`, `group_idgroup`, `ressource_idressource`),
  INDEX `fk_group_has_ressource_ressource1_idx` (`ressource_idressource` ASC),
  INDEX `fk_group_has_ressource_group1_idx` (`group_idgroup` ASC),
  CONSTRAINT `fk_group_has_ressource_group1`
    FOREIGN KEY (`group_idgroup`)
    REFERENCES `group` (`idgroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_has_ressource_ressource1`
    FOREIGN KEY (`ressource_idressource`)
    REFERENCES `ressource` (`idressource`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
