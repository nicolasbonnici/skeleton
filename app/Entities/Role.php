<?php

namespace app\Entities;

/**
 * Block entity
 *
 * @author infradmin
 */
class Role extends \Library\Core\Entity {

    const ENTITY = 'Role';
    const TABLE_NAME = 'role';
    const PRIMARY_KEY = 'idrole';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 120; 
    
    protected $aLinkedEntities;
    
}

?>
