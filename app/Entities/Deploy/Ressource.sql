-- -----------------------------------------------------
-- Table `ressource`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ressource` ;

CREATE TABLE IF NOT EXISTS `ressource` (
  `idressource` INT(15) NOT NULL,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`idressource`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;

