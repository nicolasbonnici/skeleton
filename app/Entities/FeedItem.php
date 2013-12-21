<?php

namespace app\Entities;

/**
 * Description of Feed
 *
 * @author infradmin
 */
class FeedItem extends \Library\Core\Entity {

    const ENTITY = 'FeedItem';
    const TABLE_NAME = 'feedItems';
    const PRIMARY_KEY = 'idfeedItem';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 50;    

    protected $aLinkedEntities = array(
        'feed' => array(
            'loadByDefault' => false,
            'relationship' => 'oneToOne', // @see oneToOne|manyToOne|manyToMany
    		'entity' => 'Feed',
    		'mappedByField' => 'feeds_idfeed'
        )
    );
    
}

?>
