<?php

namespace app\Entities;

/**
 * Description of Tag
 *
 * @author infradmin
 */
class Tag  extends \Library\Core\Entity {

    const ENTITY = 'Tag';
    const TABLE_NAME = 'tag';
    const PRIMARY_KEY = 'idtag';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 50;
    protected $bIsSearchable = true;

    protected $aLinkedEntities = array();

    protected $sStructure = "
    -- -----------------------------------------------------
    -- Table `tag`
    -- -----------------------------------------------------
    DROP TABLE IF EXISTS `tag` ;

    CREATE TABLE IF NOT EXISTS `tag` (
      `idtag` INT(15) NOT NULL AUTO_INCREMENT,
      `label` VARCHAR(70) NULL,
      `created` INT(15) NULL,
      PRIMARY KEY (`idtag`))
    ENGINE = InnoDB;
    ";

}
