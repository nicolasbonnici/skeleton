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

    protected $aLinkedEntities = array();
    
}

?>
