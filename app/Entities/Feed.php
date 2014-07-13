<?php

namespace app\Entities;

/**
 * Description of Feed
 *
 * @author infradmin
 */
class Feed extends \Library\Core\Entity {

    const ENTITY = 'Feed';
    const TABLE_NAME = 'feed';
    const PRIMARY_KEY = 'idfeed';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 50;

    protected $bIsSearchable = true;

    protected $aLinkedEntities = array();

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
