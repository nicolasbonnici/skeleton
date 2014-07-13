<?php

namespace app\Entities;

/**
 * \app\Entity\User Group Entity
 *
 * @see \app\Entities\User
 * @author infradmin
 */
class Config extends \Library\Core\Entity {

    const ENTITY = 'Config';
    const TABLE_NAME = 'config';
    const PRIMARY_KEY = 'idconfig';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 120;

    protected $aLinkedEntities;

    protected $sStructure = "
    -- -----------------------------------------------------
    -- Table `config`
    -- -----------------------------------------------------
    DROP TABLE IF EXISTS `config` ;

    CREATE TABLE IF NOT EXISTS `config` (
      `idconfigVar` INT(15) NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(255) NOT NULL,
      `value` TEXT NULL,
      `lastupdate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`idconfigVar`),
      INDEX `name` (`name` ASC),
      UNIQUE INDEX `name_UNIQUE` (`name` ASC))
    ENGINE = InnoDB
    COMMENT = 'Stock les variables de config du core et des modules';
    ";

}
