
-- -----------------------------------------------------
-- Table `mediaType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mediaType` ;

CREATE TABLE IF NOT EXISTS `mediaType` (
  `idmediaType` INT(15) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(45) NULL,
  PRIMARY KEY (`idmediaType`))
ENGINE = InnoDB;

