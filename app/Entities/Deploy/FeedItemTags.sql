-- -----------------------------------------------------
-- Table `feeditemTags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `feeditemTags` ;

CREATE TABLE IF NOT EXISTS `feeditemTags` (
  `idfeeditemtags` INT(15) NOT NULL AUTO_INCREMENT,
  `tag_idtag` INT(15) NOT NULL,
  `feedItem_idfeedItem` INT NOT NULL,
  PRIMARY KEY (`idfeeditemtags`, `tag_idtag`, `feedItem_idfeedItem`),
  INDEX `fk_tags_has_feedItems_feedItems1_idx` (`feedItem_idfeedItem` ASC),
  INDEX `fk_tags_has_feedItems_tags1_idx` (`tag_idtag` ASC),
  CONSTRAINT `fk_tags_has_feedItems_tags1`
    FOREIGN KEY (`tag_idtag`)
    REFERENCES `tag` (`idtag`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tags_has_feedItems_feedItems1`
    FOREIGN KEY (`feedItem_idfeedItem`)
    REFERENCES `feedItem` (`idfeedItem`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
