-- -----------------------------------------------------
-- Table `media`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `media` ;

CREATE TABLE IF NOT EXISTS `media` (
  `idmedia` INT(15) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(90) NULL,
  `desc` TEXT NULL,
  `path` VARCHAR(256) NULL,
  `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `user_iduser` INT(15) NOT NULL,
  `mediaType_idmediaType` INT(15) NOT NULL,
  PRIMARY KEY (`idmedia`, `user_iduser`, `mediaType_idmediaType`),
  INDEX `fk_media_user1_idx` (`user_iduser` ASC),
  INDEX `fk_media_mediaType1_idx` (`mediaType_idmediaType` ASC),
  CONSTRAINT `fk_media_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_media_mediaType1`
    FOREIGN KEY (`mediaType_idmediaType`)
    REFERENCES `mediaType` (`idmediaType`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
