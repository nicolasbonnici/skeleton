
-- -----------------------------------------------------
-- Table `route`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `route` ;

CREATE TABLE IF NOT EXISTS `route` (
  `idroute` INT NOT NULL,
  `path` VARCHAR(255) NULL,
  `module` VARCHAR(96) NULL,
  `controller` VARCHAR(96) NULL,
  `action` VARCHAR(96) NULL,
  PRIMARY KEY (`idroute`),
  UNIQUE INDEX `path_UNIQUE` (`path` ASC))
ENGINE = InnoDB;
