
-- -----------------------------------------------------
-- Table `link`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `link` ;

CREATE TABLE IF NOT EXISTS `link` (
  `idlink` INT(15) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NULL,
  `class` VARCHAR(45) NULL,
  `url` VARCHAR(500) NULL,
  `module` VARCHAR(128) NULL,
  `controller` VARCHAR(128) NULL,
  `action` VARCHAR(128) NULL,
  `is_internal` TINYINT(1) NULL DEFAULT 0,
  `menu_id` INT NOT NULL,
  PRIMARY KEY (`idlink`),
  INDEX `fk_cms_links_cms_menus1_idx` (`menu_id` ASC),
  UNIQUE INDEX `label_UNIQUE` (`label` ASC),
  CONSTRAINT `fk_cms_linksMenus_id`
    FOREIGN KEY (`menu_id`)
    REFERENCES `menu` (`idmenu`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
