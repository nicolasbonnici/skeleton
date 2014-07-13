
-- -----------------------------------------------------
-- Table `menu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu` ;

CREATE TABLE IF NOT EXISTS `menu` (
  `idmenu` INT(15) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(45) NOT NULL,
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`idmenu`),
  UNIQUE INDEX `label_UNIQUE` (`label` ASC))
ENGINE = InnoDB;
