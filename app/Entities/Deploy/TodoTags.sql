-- -----------------------------------------------------
-- Table `todoTags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `todoTags` ;

CREATE TABLE IF NOT EXISTS `todoTags` (
  `idtodotag` INT(15) NOT NULL AUTO_INCREMENT,
  `tag_idtag` INT(15) NOT NULL,
  `todo_idtodo` INT NOT NULL,
  PRIMARY KEY (`idtodotag`, `tag_idtag`, `todo_idtodo`),
  INDEX `fk_tags_has_Todo_Todo1_idx` (`todo_idtodo` ASC),
  INDEX `fk_tags_has_Todo_tags1_idx` (`tag_idtag` ASC),
  CONSTRAINT `fk_tags_has_Todo_tags1`
    FOREIGN KEY (`tag_idtag`)
    REFERENCES `tag` (`idtag`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tags_has_Todo_Todo1`
    FOREIGN KEY (`todo_idtodo`)
    REFERENCES `todo` (`idtodo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
