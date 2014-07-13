
-- -----------------------------------------------------
-- Table `comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `comment` ;

CREATE TABLE IF NOT EXISTS `comment` (
  `idcomment` INT(15) NOT NULL AUTO_INCREMENT,
  `state` TINYINT(1) NULL,
  `author` VARCHAR(128) NULL,
  `url` VARCHAR(255) NULL,
  `comment` TEXT NULL,
  `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `post_idpost` INT NOT NULL,
  PRIMARY KEY (`idcomment`, `post_idpost`),
  INDEX `fk_comment_posts1_idx` (`post_idpost` ASC),
  CONSTRAINT `fk_comment_posts1`
    FOREIGN KEY (`post_idpost`)
    REFERENCES `post` (`idpost`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

