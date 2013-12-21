<?php

namespace app\Entities;

/**
 * BlockType entity
 *
 * @author infradmin
 */
class BlockType extends \Library\Core\Entity {

    const ENTITY = 'BlockType';
    const TABLE_NAME = 'blockTypes';
    const PRIMARY_KEY = 'idblockType';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 60;    
    
    protected $aLinkedEntities;
    
}

?>
