<?php

namespace app\Entities;

/**
 * Block entity
 *
 * @author infradmin
 */
class PostCollection extends \Library\Core\EntitiesCollection {

	const ENTITY = 'Post';
    const TABLE_NAME = 'posts';
    const PRIMARY_KEY = 'idpost';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 600;    
        
}

?>
