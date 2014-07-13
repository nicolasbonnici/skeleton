
-- -----------------------------------------------------
-- Table `feedItem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `feedItem` ;

CREATE TABLE IF NOT EXISTS `feedItem` (
  `idfeedItem` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(256) NULL,
  `content` TEXT NULL,
  `permalink` VARCHAR(256) NULL,
  `status` VARCHAR(45) NULL,
  `created` INT(15) NULL,
  `feed_idfeed` INT NOT NULL,
  PRIMARY KEY (`idfeedItem`, `feed_idfeed`),
  INDEX `fk_feedItem_feeds1_idx` (`feed_idfeed` ASC),
  CONSTRAINT `fk_feedItem_feeds1`
    FOREIGN KEY (`feed_idfeed`)
    REFERENCES `feed` (`idfeed`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
