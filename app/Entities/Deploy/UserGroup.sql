-- -----------------------------------------------------
-- Table `userGroups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `userGroups` ;

CREATE TABLE IF NOT EXISTS `userGroups` (
  `idusergroup` INT(15) NOT NULL AUTO_INCREMENT,
  `user_iduser` INT(15) NOT NULL,
  `group_idgroup` INT(15) NOT NULL,
  `created` INT(15) NULL,
  PRIMARY KEY (`idusergroup`, `user_iduser`, `group_idgroup`),
  INDEX `fk_user_has_group_group1_idx` (`group_idgroup` ASC),
  INDEX `fk_user_has_group_user1_idx` (`user_iduser` ASC),
  CONSTRAINT `fk_user_has_group_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_group_group1`
    FOREIGN KEY (`group_idgroup`)
    REFERENCES `group` (`idgroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
