-- -----------------------------------------------------
-- Table `feed`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `feed` ;

CREATE TABLE IF NOT EXISTS `feed` (
  `idfeed` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `icon` VARCHAR(255) NULL,
  `public_url` VARCHAR(255) NULL,
  `url` TEXT NOT NULL,
  `status` TINYINT(1) NULL,
  `domain` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idfeed`))
ENGINE = InnoDB;
