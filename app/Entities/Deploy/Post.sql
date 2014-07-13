-- -----------------------------------------------------
-- Table `post`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `post` ;

CREATE TABLE IF NOT EXISTS `post` (
  `idpost` INT NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(45) NOT NULL,
  `thumb_url` VARCHAR(254) NULL,
  `title` VARCHAR(75) NOT NULL,
  `content` TEXT NULL,
  `lastupdate` INT(15) NULL,
  `created` INT(15) NULL,
  `status` VARCHAR(45) NOT NULL DEFAULT 'unpublished',
  `user_iduser` INT(15) NOT NULL,
  PRIMARY KEY (`idpost`, `user_iduser`),
  INDEX `fk_post_user1_idx` (`user_iduser` ASC),
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC),
  CONSTRAINT `fk_post_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

