
-- -----------------------------------------------------
-- Table `tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tag` ;

CREATE TABLE IF NOT EXISTS `tag` (
  `idtag` INT(15) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(70) NULL,
  `created` INT(15) NULL,
  PRIMARY KEY (`idtag`))
ENGINE = InnoDB;
