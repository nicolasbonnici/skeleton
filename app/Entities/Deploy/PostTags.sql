-- -----------------------------------------------------
-- Table `postTags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postTags` ;

CREATE TABLE IF NOT EXISTS `postTags` (
  `post_idpost` INT NOT NULL,
  `category_idcategory` INT(15) NOT NULL,
  `tag_idtag` INT(15) NOT NULL,
  PRIMARY KEY (`post_idpost`, `category_idcategory`, `tag_idtag`),
  INDEX `fk_post_has_tag_tag1_idx` (`tag_idtag` ASC),
  INDEX `fk_post_has_tag_post1_idx` (`post_idpost` ASC, `category_idcategory` ASC),
  CONSTRAINT `fk_post_has_tag_post1`
    FOREIGN KEY (`post_idpost`)
    REFERENCES `post` (`idpost`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_has_tag_tag1`
    FOREIGN KEY (`tag_idtag`)
    REFERENCES `tag` (`idtag`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
